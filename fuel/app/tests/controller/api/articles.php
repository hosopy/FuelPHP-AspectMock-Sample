<?php

/**
 * Controller_Api_Articlesのテスト
 *
 * @group Api
 */

class Test_Controller_Api_Articles extends \Fuel\Core\TestCase
{
	/**
	 * get_recommended() のテストコード1
	 *
	 * DBにデータを作成した上で、データ取得のロジックまで検証
	 */
	public function test_get_articles_recommended_json_1()
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

		// HMVCによるRequest発行
		$request = \Request::forge('api/articles/recommended');
		$response = $request->execute()->response();

		/*
		 * Responseの検証
		 * Controllerのテストとして最低限必須と思われるもの
		 */
		// ステータスコードのテスト
		$this->assertEquals(200, $response->status);
		// headerのテスト
		$this->assertEquals('application/json', $response->headers['Content-Type']);
		// bodyのテスト
		$json = json_decode($response->body);
		$this->assertCount(5, $json);
		// Objectの属性
		foreach ($json as $index => $article)
		{
			$this->assertObjectHasAttribute('id', $article);
			$this->assertObjectHasAttribute('title', $article);
			$this->assertObjectHasAttribute('body', $article);
			$this->assertObjectHasAttribute('rank', $article);
			$this->assertObjectHasAttribute('created_at', $article);
		}
		
		/*
		 * Controllerのテストとして不要と思われるが、議論のために
		 */
		// rankの降順に並んでいることをテスト
		// Model_Article::get_recommended()のテストで担保されるべき
		$response_ranks = \Arr::pluck($json, 'rank');
		$prev_rank = PHP_INT_MAX;
		foreach ($response_ranks as $rank)
		{
			$this->assertLessThanOrEqual($prev_rank, $rank);
			$prev_rank = $rank;
		}
	}

	/**
	 * get_recommended() のテストコード2
	 *
	 * Model_Article::get_latest() をモック化して検証
	 */
	public function test_get_articles_recommended_json_2()
	{
		// Model_Article::get_recommended() をモックに置き換え
		$articles = array(
			Model_Article::forge(array('id' => 1, 'title' => 'Title 0', 'body' => 'Body 0', 'rank' => 100, 'created_at' => time())),
			Model_Article::forge(array('id' => 2, 'title' => 'Title 1', 'body' => 'Body 1', 'rank' => 200, 'created_at' => time())),
		);
		\AspectMock\Test::double('Model_Article', array('get_recommended' => $articles));

		// HMVCによるRequest発行
		$request = \Request::forge('api/articles/recommended');
		$response = $request->execute()->response();

		/*
		 * Responseの検証
		 * Controllerのテストとして最低限必須と思われるもの
		 */
		// ステータスコードのテスト
		$this->assertEquals(200, $response->status);
		// headerのテスト
		$this->assertEquals('application/json', $response->headers['Content-Type']);
		// bodyのテスト
		$json = json_decode($response->body);
		$this->assertCount(2, $json);
		// Objectの属性
		foreach ($json as $index => $article)
		{
			$this->assertObjectHasAttribute('id', $article);
			$this->assertObjectHasAttribute('title', $article);
			$this->assertObjectHasAttribute('body', $article);
			$this->assertObjectHasAttribute('rank', $article);
			$this->assertObjectHasAttribute('created_at', $article);
		}
	}

	protected function tearDown()
	{
		// DBをクリア
		\DBUtil::truncate_table(Model_Article::get_table_name());

		// AspectMockのモックをクリア
		\AspectMock\Test::clean();
	}
}
