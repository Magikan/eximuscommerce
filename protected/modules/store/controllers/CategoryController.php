<?php

/**
 * Display category products
 * TODO: Add default sorting by rating, etc...
 *
 * @property $activeAttributes
 * @property $eavAttributes
 */
class CategoryController extends Controller
{

	/**
	 * @var StoreProduct
	 */
	public $query;

	/**
	 * @var StoreCategory
	 */
	public $model;

	/**
	 * @var array Eav attributes used in http query
	 */
	private $_eavAttributes;

	/**
	 * @var array
	 */
	public $allowedPageLimit = array(12,18,24);

	/**
	 * Current query clone to use in min/max price queries
	 * @var CDbCriteria
	 */
	public $currentQuery;

	/**
	 * @var CActiveDataProvider
	 */
	public $provider;

	/**
	 * @var string
	 */
	private $_minPrice;

	/**
	 * @var string
	 */
	private $_maxPrice;

	/**
	 * Load category model by url
	 * @return bool
	 */
	public function beforeAction()
	{
		$this->model = $this->_loadModel(Yii::app()->request->getQuery('url'));

		if(Yii::app()->request->getPost('min_price') || Yii::app()->request->getPost('max_price'))
		{
			$data=array();
			if(Yii::app()->request->getPost('min_price'))
				$data['min_price']=(int)Yii::app()->request->getPost('min_price');
			if(Yii::app()->request->getPost('max_price'))
				$data['max_price']=(int)Yii::app()->request->getPost('max_price');
			$this->redirect(Yii::app()->request->addUrlParam('/store/category/view', $data));
		}

		return true;
	}

	/**
	 * Display products list
	 */
	public function actionView()
	{
		$this->query = new StoreProduct(null);
		$this->query->attachBehaviors($this->query->behaviors());
		$this->query->applyCategories($this->model)
			->applyAttributes($this->activeAttributes)
			->active();

		 // Filter by manufacturer
		if(Yii::app()->request->getQuery('manufacturer'))
		{
			$manufacturers = explode(';', Yii::app()->request->getParam('manufacturer', ''));
			$this->query->applyManufacturers($manufacturers);
		}

		// Create clone of the current query to use later to get min and max prices.
		$this->currentQuery = clone $this->query->getDbCriteria();

		// Filter products by price range if we have min_price or max_price in request
		$this->query->applyMinPrice(Yii::app()->request->getQuery('min_price'));
		$this->query->applyMaxPrice(Yii::app()->request->getQuery('max_price'));

		$per_page = $this->allowedPageLimit[0];
		if(isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $this->allowedPageLimit))
			$per_page = (int) $_GET['per_page'];

		$this->provider = new CActiveDataProvider($this->query, array(
			// Set id to false to not display model name in
			// sort and page params
			'id'=>false,
			'pagination'=>array(
				'pageSize'=>$per_page,
			)
		));

		$this->provider->sort = StoreProduct::getCSort();

		$view = $this->setDesign($this->model, 'view');
		$this->render($view, array(
			'provider'=>$this->provider,
			'itemView'=>(isset($_GET['view']) && $_GET['view']==='wide') ? '_product_wide' : '_product'
		));
	}

	/**
	 * @return array of attributes used in http query and available in category
	 */
	public function getActiveAttributes()
	{
		$data = array();

		foreach(array_keys($_GET) as $key)
		{
			if(array_key_exists($key, $this->eavAttributes))
			{
				if((boolean) $this->eavAttributes[$key]->select_many === true)
					$data[$key] = explode(';', $_GET[$key]);
				else
					$data[$key] = array($_GET[$key]);
			}
		}

		return $data;
	}

	/**
	 * @return array of available attributes in category
	 */
	public function getEavAttributes()
	{
		if(is_array($this->_eavAttributes))
			return $this->_eavAttributes;

		// Find category types
		$model = new StoreProduct(null);
		$criteria = $model
			->applyCategories($this->model)
			->active()
			->getDbCriteria();

		unset($model);

		$builder = new CDbCommandBuilder(Yii::app()->db->getSchema());

		$criteria->select    = 'type_id';
		$criteria->group     = 'type_id';
		$criteria->distinct  = true;
		$typesUsed = $builder->createFindCommand(StoreProduct::tableName(), $criteria)->queryColumn();

		// Find attributes by type
		$criteria = new CDbCriteria;
		$criteria->addInCondition('types.type_id', $typesUsed);
		$query = StoreAttribute::model()
			->useInFilter()
			->with(array('types', 'options'))
			->findAll($criteria);

		$this->_eavAttributes = array();
		foreach($query as $attr)
			$this->_eavAttributes[$attr->name] = $attr;
		return $this->_eavAttributes;
	}

	/**
	 * @return string min price
	 */
	public function getMinPrice()
	{
		if($this->_minPrice!==null)
			return $this->_minPrice;
		$this->_minPrice=$this->aggregatePrice();
		return $this->_minPrice;
	}

	/**
	 * @return string max price
	 */
	public function getMaxPrice()
	{
		if($this->_maxPrice!==null)
			return $this->_maxPrice;
		$this->_maxPrice=$this->aggregatePrice('MAX');
		return $this->_maxPrice;
	}

	/**
	 * @param string $function
	 * @return mixed
	 */
	public function aggregatePrice($function = 'MIN')
	{
		$criteria = new CDbCriteria;
		$criteria->select = $function.'(t.price) as aggregation_price';

		$current_query = clone $this->currentQuery;
		$query = new StoreProduct(null);
		$query->getDbCriteria()->mergeWith($current_query);
		$query = $query->find($criteria);
		if($query)
			return $query->aggregation_price;
		return null;
	}

	/**
	 * Load category by url
	 * @param $url
	 * @return StoreCategory
	 * @throws CHttpException
	 */
	public function _loadModel($url)
	{
		// Find category
		$model = StoreCategory::model()
			->withUrl($url)
			->find();

		if (!$model) throw new CHttpException(404, Yii::t('StoreModule.core', 'Категория не найдена.'));

		return $model;
	}
}
