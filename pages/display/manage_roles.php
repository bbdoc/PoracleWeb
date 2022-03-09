<!-- Title -->

<?php

        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
        echo '<strong>'.i8ln("Role Management").'</strong>';
        echo '</div>';

#$roles = @file_get_contents("roles.json", false);
$roles= @file_get_contents("$api_address/api/humans/".$_SESSION['id']."/roles", false, $context);
$json = json_decode($roles, true);

if ( count($json) == 0 ) {
	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
	echo i8ln("Sorry Nothing to Show");
	echo '</div>';
}
else {

?>

<center>
<form action='#' method='POST' id='search'>
<select name='guild' class='form-select form-select-lg m-1' style='height:35px;'>

<?php

if ( isset($_POST['guild'])) { $_SESSION['guild'] = $_POST['guild']; }  
if ( !isset($_POST['guild']) && !isset($_SESSION['guild']) ) { $_SESSION['guild'] = $json['guilds'][0]['name']; }  

foreach ($json['guilds'] as $key => $value) {
	if ( $value['name'] == $_SESSION['guild'] ) { $selected = "selected"; } else { $selected = ""; }
	echo "<option value='".$value['name']."' $selected>".$value['name']."</option>\n";
}

?>

</select>
<button type='submit' id='submit' name='submit' class='btn btn-light'><i class='fas fa-search'></i></button>
</form>
</center>
<br>

<?php

// Exlusives

foreach ($json['guilds'] as $key => $value) {
        if ( $value['name'] == $_SESSION['guild'] ) {
		foreach ($value['roles']['exclusive'] as $key => $value) {
			echo "<div class='row'>";
			echo "   <div class='col'>";
			echo "      <div class='alert alert-secondary text-center'>"; 
			echo "      <p>".i8ln("Select Max 1 option")."</p>";
			$exclusive_id = $key;
			foreach ($value as $key => $value) {
				if ( $value['set'] == "True" ) {
					echo "<a href='./actions/roles.php?type=remove&id=".$value['id']."'>";
					echo "<button type='button' class='btn btn-success m-1'>".$value['description']."</button>";
					echo "</a>";
				} else {
					echo "<a href='./actions/roles.php?type=add&id=".$value['id']."'>";
					echo "<button type='button' class='btn btn-light m-1'>".$value['description']."</button>";
					echo "</a>";
				}
                        }
			echo "      </div>";
			echo "   </div>";
			echo "</div>";
                }
        }
}

// General


foreach ($json['guilds'] as $key => $value) {
	if ( $value['name'] == $_SESSION['guild'] ) {
		if (count($value['roles']['general']) > 0 ) {
		        echo "<div class='row'>";
                        echo "   <div class='col'>";
		        echo "      <div class='alert alert-secondary text-center'>";
		        echo "      <p>".i8ln("Select multiple options")."</p>";
			foreach ($value['roles']['general'] as $key => $value) { 
				if ( $value['set'] == "True" ) {
                                        echo "<a href='./actions/roles.php?type=remove&id=".$value['id']."'>";
                                        echo "<button type='button' class='btn btn-success m-1'>".$value['description']."</button>";
                                        echo "</a>";
                                } else {
                                        echo "<a href='./actions/roles.php?type=add&id=".$value['id']."'>";
                                        echo "<button type='button' class='btn btn-light m-1'>".$value['description']."</button>";
                                        echo "</a>";
				}
                        }
                        print_r($value['roles']['general']);
                        echo "      </div>";
                        echo "   </div>";
			echo "</div>";
		}
        }
}

}

?>
