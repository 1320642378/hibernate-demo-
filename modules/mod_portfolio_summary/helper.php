<?php 
defined('_JEXEC') or die;

abstract Class ModPortfolioSummaryHelper{
	//response json
	public static function getResult(&$params){
 		$result=self::getData($params);
		$jsonString= json_encode($result);
		return $jsonString;
	}
	//response array
	public static function getResultArray(&$params){
      $db = JFactory::getDbo();
      $sql = 'SELECT id FROM #__users ';
      $db->setQuery($sql);
      $userIds = $db->loadResultArray();
      echo implode(', ', $userIds);
	}
	//select data from database
	protected static function getData(&$params){
		  //current user
		$user = JFactory::getUser();
		$dbo = JFactory::getDbo();

		$asofDate=new Datetime();
		$asofDate->setDate(2014,4,1);

		$c=new Datetime();
		$c->setDate(2014,7,1);
		//get bpId
		$query = $dbo->getQuery(true);
		$query -> select($dbo->quoteName('client_setup_bp_id'))
		       ->from($dbo->quoteName('#__clients_setup'))
			   ->where($dbo->quoteName('client_setup_user_id') .'=\''. $user->get('username') .'\'');
		$dbo->setQuery($query);
		$bpId=$dbo->loadResult();
		//bp
		$container_type=array("fx_margin_cont", "portf_clt_dvp", "portf_clt","insu_cont","sbl_cont");
		$query=$dbo->getQuery(true);
		$query->select(array('bp.*'))
		      ->from($dbo->qn("#__business_partners","bp"))
			  ->innerJoin("#__containers as cont on cont.bp_id=bp.bp_id")
		      ->innerJoin("#__relationship_managers as rm on rm.sales_code=bp.frontsales_code")
			  ->where("cont.cont_type in ('" .implode('\',\'',$container_type) .'\')')
		      ->where("bp.contract_type is not null")->where('bp.close_date >\'' . $asofDate->format('Y-m-d').'\' or bp.close_date is not null')
		      ->where('bp.bp_id =\'' . $bpId .'\'')->where('bp.effective_from<=\'' . $asofDate->format('Y-m-d').'\'')->where('bp.effective_to>\'' . $asofDate->format('Y-m-d').'\'')
		      ->where('rm.effective_to=\'9999-12-31\'');
		$dbo->setQuery($query);
		$bp =$dbo->loadAssoc();
		
		
		//get refccy
		//$refccy=$bp["RCCY_CODE"];
		//$bp=$dbo->loadObject();
		$refccy=$bp["RCCY_CODE"];
		//get asset position (apList)
		   $includeClass = array("Bonds","Equities","Funds","Others","Structured Products","Loans");
	    $includeSubClass = array("Cash","Money Market");
		$query=$dbo->getQuery(true);
		$query->select('ap.*')
		      ->from($dbo->qn("#__asset_position","ap"))
			  ->innerJoin("#__asset as a on ap.dw_asst_id = a.id")
			  ->where("a.asset_class_1 in ('" .implode('\',\'',$includeClass) .'\')'.
			                       "or a.assetClass2 in ('" .implode('\',\'',$includeSubClass) .'\')')
		      ->where('ap.bp_id=\'' . $bpId . '\'')->where('ap.as_at_date=\'' . $asofDate->format("Y-m-d") . '\'')
		      ->where('ap.dw_cont_id in (select c.id from #__containers as c where c.cont_type in (\'sbl_cont\') )')
			  ->where("a.ASSET_COLLAT_SUB_TYPE_INTL_ID is null or a.ASSET_COLLAT_SUB_TYPE_INTL_ID != 'virt_cust'")
		      
			  ->order('a.asset_class_1','a.asset_class_2');
		$dbo->setQuery($query,0,1);
		$apList=$dbo->loadObjectList();
		//get asset position (sblEquitiesList)
		$query=$dbo->getQuery(true);
		$query->select('ap.*')
		      ->from($dbo->qn("#__asset_position","ap"))
		      ->innerJoin("#__asset as a on ap.dw_asst_id = a.id")
		      ->where("a.asset_class_1='Equities'")
		      ->where('ap.bp_id=\'' . $bpId . '\'')->where('ap.as_at_date=\'' . $asofDate->format("Y-m-d") . '\'')
		      ->where('ap.dw_cont_id not in (select c.id from #__containers as c where c.cont_type in (\'sbl_cont\') )')
		      ->where('a.ASSET_COLLAT_SUB_TYPE_INTL_ID is null or a.ASSET_COLLAT_SUB_TYPE_INTL_ID in (\'virt_cust\')')
		      ->order('ap.cont_name')->order('a.asset_class_1')->order('a.asset_class_2')->order('a.positionCcy')
		      ->order('a.asset_name_long');
		$dbo->setQuery($query,0,1);
		$sblEquitiesList=$dbo->loadObjectList();			
		
		//get asset position
		$query=$dbo->getQuery(true);
		$query->select('ap.*')->from($dbo->qn("#__asset_position","ap"))->innerJoin("#__asset as a on ap.dw_asst_id = a.id")->where('a.asset_class_1=\'' . $assetClass1 . '\'')
		->where('ap.bp_id=\'' . $bpId . '\'')->where('ap.as_at_date=\'' . $asofDate->format("Y-m-d") . '\'')->where('a.ASSET_COLLAT_SUB_TYPE_INTL_ID is null or a.ASSET_COLLAT_SUB_TYPE_INTL_ID in (\'virt_cust\')')
		->where('ap.dw_cont_id not in (select c.id from #__containers as c where c.cont_type in (\'sbl_cont\') )')->order('ap.cont_name')->order('a.asset_class_1')->order('asset_class_2')->order('a.MATURITY_DATE')
		->order('ap.POSITION_CCY')->order('a.asset_name_long');
		$dbo->setQuery($query,0,1);
		$assetPositions=$dbo->loadObjectList();
		
		//get assets
		$query=$dbo->getQuery(true);
		$query->select('a.*')->from($dbo->qn('#__asset','a'))->where("a.asset_class_3 in ('Currency','Virtual Metal')")->where('a.asset_name=\'' .$refccy.'\'')
		->where('a.effective_from<=\''. $asofDate->format('Y-m-d').'\'')->where('a.effective_to>\''. $asofDate->format('Y-m-d').'\'');
		$dbo->setQuery($query,0,1);
		$unitForBp=$dbo->loadObjectList();
		
    
	}
}