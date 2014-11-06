<?php

class Controller_Api_Articles extends Controller_Rest
{
	/**
	 * GET /api/articles/recommended[.format]
	 *
	 * おすすめ記事リストを取得
	 */
	public function get_recommended()
	{
		return $this->response(array_values(Model_Article::get_recommended()));
	}
}
