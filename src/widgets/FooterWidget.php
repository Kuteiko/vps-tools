<?php
	namespace vps\tools\widgets;

	use yii\base\Widget;
	use yii\web\View;

	/**
	 * Widget footer site
	 * Class FooterWidget
	 * @package vps\tools\widgets
	 */
	class FooterWidget extends Widget
	{
		/**
		 * Copyright from date
		 *
		 * ```php
		 * copyrightFrom = 2017
		 * ```
		 */
		public $copyrightFrom;

		/**
		 * Name company and url
		 *
		 * ```php
		 * company = [ 'title' => '', 'url' => '' ]
		 * ```
		 */
		public $company = [ 'title' => '', 'url' => '' ];

		/**
		 * Array links in center footer
		 *
		 * ```php
		 * links = [[ 'title' => '', 'url' => '' ],[ 'title' => '', 'url' => '' ]]
		 * ```
		 */
		public $links = [];

		/**
		 * Class "container-fluid" or "container"
		 *
		 * @var bool
		 */
		public $fluid = false;

		/**
		 * @inheritdoc
		 */
		public function init ()
		{
			parent::init();
			$this->view = new View([
				'renderers' => [
					'tpl' => [
						'class'   => 'yii\smarty\ViewRenderer',
						'imports' => [
							'Html' => '\vps\tools\helpers\Html',
							'Url'  => '\vps\tools\helpers\Url'
						]
					]
				]
			]);
		}

		/**
		 * @inheritdoc
		 */
		public function run ()
		{

			return $this->renderFile(__DIR__ . '/views/footer.tpl', [
				'copyrightFrom' => $this->copyrightFrom,
				'company'       => $this->company,
				'links'         => $this->links,
				'fluid'         => $this->fluid,
			]);
		}
	}