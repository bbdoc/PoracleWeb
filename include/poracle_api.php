<?php

// HTTP client for the PoracleNG API. Transport failures and any status >= 400
// are thrown as PoracleApiException so actions can report them in one place.

class PoracleApiException extends RuntimeException
{
   public $status;
   public $detail;
   public $errors;

   public function __construct($status, $detail, array $errors = array())
   {
      parent::__construct($detail, (int) $status);
      $this->status = (int) $status;
      $this->detail = $detail;
      $this->errors = $errors;
   }

   // The detail plus each field error, for showing to the user.
   public function userMessage()
   {
      $parts = array();
      foreach ($this->errors as $e) {
         $location = isset($e['location']) ? $e['location'] . ': ' : '';
         $parts[] = $location . (isset($e['message']) ? $e['message'] : '');
      }
      return $parts ? $this->detail . ' (' . implode('; ', $parts) . ')' : $this->detail;
   }
}

class PoracleApi
{
   private $base;
   private $secret;
   private $transport;

   // $transport: function ($method, $url, array $headers, $body) returning
   // array($status, $responseBody). Tests inject a fake; production uses curl.
   public function __construct($base, $secret, $transport = null)
   {
      $this->base = rtrim($base, '/');
      $this->secret = $secret;
      $this->transport = $transport ? $transport : array($this, 'curlTransport');
   }

   public function get($path, array $query = array()) { return $this->request('GET', $path, $query); }
   public function post($path, $body = null, array $query = array()) { return $this->request('POST', $path, $query, $body); }
   public function put($path, $body, array $query = array()) { return $this->request('PUT', $path, $query, $body); }
   public function patch($path, $body, array $query = array()) { return $this->request('PATCH', $path, $query, $body); }
   public function delete($path, array $query = array()) { return $this->request('DELETE', $path, $query); }

   public function request($method, $path, array $query = array(), $body = null)
   {
      $url = $this->base . $path;
      if ($query) {
         $url .= '?' . self::buildQuery($query);
      }
      $headers = array('Accept: application/json', 'X-Poracle-Secret: ' . $this->secret);
      $payload = null;
      if ($body !== null) {
         $payload = json_encode($body);
         $headers[] = 'Content-Type: application/json';
      }

      list($status, $raw) = call_user_func($this->transport, $method, $url, $headers, $payload);
      if ((int) $status === 0) {
         throw new PoracleApiException(0, 'Poracle unavailable');
      }
      $json = ($raw === null || $raw === '') ? null : json_decode($raw, true);
      if ($status >= 400) {
         throw self::errorFrom((int) $status, $json, $raw);
      }
      return $json;
   }

   // v2 rejects "1"/"" for booleans: send true/false.
   public static function buildQuery(array $query)
   {
      foreach ($query as $key => $value) {
         if (is_bool($value)) {
            $query[$key] = $value ? 'true' : 'false';
         }
      }
      return http_build_query($query);
   }

   private static function errorFrom($status, $json, $raw)
   {
      if (is_array($json) && (isset($json['detail']) || isset($json['title']))) {
         // RFC 9457 problem+json (v2 and the huma-served /api/* endpoints)
         $detail = (isset($json['detail']) && $json['detail'] !== '') ? $json['detail'] : $json['title'];
         $errors = (isset($json['errors']) && is_array($json['errors'])) ? $json['errors'] : array();
         return new PoracleApiException($status, $detail, $errors);
      }
      if (is_array($json) && isset($json['message'])) {
         // legacy v1 {status:"error", message}
         return new PoracleApiException($status, $json['message']);
      }
      return new PoracleApiException($status, ($raw === null || $raw === '') ? 'HTTP ' . $status : substr($raw, 0, 200));
   }

   public function curlTransport($method, $url, array $headers, $body)
   {
      $ch = curl_init($url);
      curl_setopt_array($ch, array(
         CURLOPT_CUSTOMREQUEST => $method,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => $headers,
         CURLOPT_CONNECTTIMEOUT => 5,
         CURLOPT_TIMEOUT => 15,
      ));
      if ($body !== null) {
         curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      }
      $raw = curl_exec($ch);
      $status = ($raw === false) ? 0 : (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
      curl_close($ch);
      return array($status, $raw === false ? null : $raw);
   }
}
