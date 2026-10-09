<?php
use PHPUnit\Framework\TestCase;

class PoracleFormsTest extends TestCase
{
   private $minCp = array(500 => 0, 1500 => 1400, 2500 => 2350);

   public function testRadioUsesPrefixNotLtrim()
   {
      $this->assertSame('-1', form_radio(array('size' => 'size_-1'), 'size_'));
      $this->assertSame('1', form_radio(array('x' => 'clean_1'), 'clean_'));
      $this->assertNull(form_radio(array(), 'clean_'));
   }

   public function testCommonFields()
   {
      $this->assertSame(array('distance' => 250, 'clean' => true, 'template' => 'big', 'ping' => '<@&1>'),
         form_common(array('distance' => '250', 'clean' => 'clean_1', 'template' => 'big', 'content' => ' <@&1> ')));
      $this->assertSame(array('distance' => null, 'clean' => null, 'template' => null, 'ping' => null),
         form_common(array('distance' => '0', 'clean' => 'clean_0', 'template' => '', 'content' => '')));
   }

   public function testQuickPickBareCleanValue()
   {
      $this->assertTrue(form_common(array('clean' => '1'))['clean']);
   }

   public function testBlankFieldsAreOmitted()
   {
      $f = poracle_compact(form_pokemon_filters(array('min_iv' => '', 'max_iv' => '', 'max_cp' => '', 'league' => '0', 'gender' => 'gender_0', 'size' => 'size_-1'), $this->minCp, 0));
      $this->assertSame(array('min_iv' => 0), $f);
   }

   public function testNoIvLeavesMinIvOut()
   {
      $f = poracle_compact(form_pokemon_filters(array('noiv' => 'on', 'min_iv' => '90'), $this->minCp, 0));
      $this->assertArrayNotHasKey('min_iv', $f);
   }

   public function testSizeAnyOmitsBoth()
   {
      $f = form_pokemon_filters(array('size' => 'size_-1'), $this->minCp, 0);
      $this->assertNull($f['size']);
      $this->assertNull($f['max_size']);
      $f = form_pokemon_filters(array('size' => 'size_3'), $this->minCp, 0);
      $this->assertSame(3, $f['size']);
      $this->assertSame(3, $f['max_size']);
   }

   public function testQuickPickPlainSize()
   {
      $f = form_pokemon_filters(array('size' => '1', 'max_size' => '1'), $this->minCp, 0);
      $this->assertSame(array(1, 1), array($f['size'], $f['max_size']));
      $f = form_pokemon_filters(array('size' => '5'), $this->minCp, 0);
      $this->assertSame(array(5, null), array($f['size'], $f['max_size']));
   }

   public function testPvpFieldsOnlyWithLeague()
   {
      $f = poracle_compact(form_pokemon_filters(array('league' => '1500', 'pvp_ranking_worst' => '10', 'pvp_ranking_best' => '', 'cap' => 'cap_50'), $this->minCp, 0));
      $this->assertSame(array('min_iv' => 0, 'pvp_ranking_league' => 1500, 'pvp_ranking_worst' => 10, 'pvp_ranking_min_cp' => 1400, 'pvp_ranking_cap' => 50), $f);
      $f = poracle_compact(form_pokemon_filters(array('league' => '0', 'pvp_ranking_worst' => '10'), $this->minCp, 0));
      $this->assertArrayNotHasKey('pvp_ranking_worst', $f);
   }

   public function testPvpDefaultCap()
   {
      $f = form_pokemon_filters(array('league' => '2500'), $this->minCp, 50);
      $this->assertSame(50, $f['pvp_ranking_cap']);
   }

   public function testGenderAndForm()
   {
      $f = form_pokemon_filters(array('gender' => 'gender_2', 'form' => 'form_46'), $this->minCp, 0);
      $this->assertSame('female', $f['gender']);
      $this->assertSame(46, $f['form']);
   }

   public function testPokemonRulesFromCheckboxes()
   {
      $rules = form_pokemon_rules(array('mon_25' => 'on', 'mon_133' => 'on', 'min_iv' => '90'), $this->minCp, 0);
      $this->assertSame(array(25, 133), array_column($rules, 'pokemon_id'));
      $this->assertSame(90, $rules[0]['min_iv']);
   }

   public function testPokemonEverythingIsNullIdAndStops()
   {
      $rules = form_pokemon_rules(array('mon_0' => 'on', 'mon_25' => 'on'), $this->minCp, 0);
      $this->assertCount(1, $rules);
      $this->assertNull($rules[0]['pokemon_id']);
   }

   public function testRaidRules()
   {
      $out = form_raid_rules(array('egg_5' => 'on', 'raid_5' => 'on', 'mon_150_0' => 'on', 'mon_6_0_1' => 'on', 'gym_id' => 'ALL', 'rsvp' => '1'));
      $this->assertCount(1, $out['egg']);
      $this->assertSame(5, $out['egg'][0]['level']);
      $this->assertNull($out['egg'][0]['gym_id']);
      $this->assertSame('rsvp', $out['egg'][0]['rsvp_changes']);
      $this->assertSame(5, $out['raid'][0]['level']);
      $this->assertNull($out['raid'][0]['pokemon_id']);
      $this->assertSame(150, $out['raid'][1]['pokemon_id']);
      $this->assertNull($out['raid'][1]['form']);
      $this->assertSame(1, $out['raid'][2]['evolution']);
   }

   public function testQuestRules()
   {
      $rules = form_quest_rules(array('mon_1_0' => 'on', 'item_1' => 'on', 'energy_0' => 'on', 'candy_25' => 'on'));
      $this->assertSame(array(7, 2, 12, 4), array_column($rules, 'reward_type'));
      $this->assertSame(array(1, 1, null, 25), array_column($rules, 'reward'));
   }

   public function testInvasionSplitsIncidents()
   {
      $out = form_invasion_rules(array('grunt_2_water' => 'on', 'grunt_0_showcase' => 'on', 'grunt_0_gold-stop' => 'on', 'grunt_0_big_boss' => 'on'));
      $this->assertSame(array('water', 'big boss'), array_column($out['invasion'], 'grunt_type'));
      $this->assertSame('female', $out['invasion'][0]['gender']);
      $this->assertSame(array(9, 7), array_column($out['incident'], 'display_type'));
   }

   public function testLureNestGym()
   {
      $this->assertSame(array(501, 506), array_column(form_lure_rules(array('lure_501' => 'on', 'lure_506' => 'on')), 'lure_id'));
      $nest = form_nest_rules(array('nest_1' => 'on', 'min_spawns' => '0'));
      $this->assertSame(1, $nest[0]['pokemon_id']);
      $this->assertNull($nest[0]['min_spawn_avg']);
      $gym = form_gym_rules(array('gym_1' => 'on', 'slot' => 'slot_1', 'battle' => 'battle_0'));
      $this->assertSame('mystic', $gym[0]['team']);
      $this->assertTrue($gym[0]['slot_changes']);
      $this->assertNull($gym[0]['battle_changes']);
   }

   public function testEnumLookups()
   {
      $this->assertSame(4, poracle_team_id(null));
      $this->assertSame(2, poracle_team_id('valor'));
      $this->assertSame(0, poracle_gender_id(null));
      $this->assertSame(3, poracle_gender_id('genderless'));
   }
}
