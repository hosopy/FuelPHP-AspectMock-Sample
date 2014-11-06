<?php

/**
 * Model_Articleのテスト
 *
 * @group Api
 */

class Test_Model_Article extends \Fuel\Core\TestCase
{
	/**
	 * Model_Article::get_recommended() のテスト
	 */
	public function test_get_recommended()
	{
		// Articleを用意
		for ($i = 0; $i < 5; $i++)
		{
			\Model_Article::forge(array(
				'title' => "Title $i",
				'body' => "Body $i",
				'rank' => rand()
			))->save();
		}
		
		$result = \Model_Article::get_recommended();
		$this->assertCount(5, $result);
		
		// rankの降順に並んでいることをテスト
		$response_ranks = \Arr::pluck($result, 'rank');
		$prev_rank = PHP_INT_MAX;
		foreach ($response_ranks as $rank)
		{
			$this->assertLessThanOrEqual($prev_rank, $rank);
			$prev_rank = $rank;
		}
	}

	protected function tearDown()
	{
		// DBをクリア
		\DBUtil::truncate_table(Model_Article::get_table_name());
	}
}
