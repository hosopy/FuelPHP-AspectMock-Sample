<?php

class Model_Article extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'title',
		'body',
		'rank',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'articles';

	public static function get_table_name()
	{
		return static::$_table_name;
	}

	/**
	 * オススメ記事リストを取得
	 *
	 * @return [Model_Article]
	 */
	public static function get_recommended()
	{
		return static::query()->order_by('rank', 'desc')->get();
	}
}
