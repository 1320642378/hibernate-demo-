<?php 
defined('_JEXEC') or die;

abstract Class mod_EquitiesHelper{
	//response json
	public static function getResult(&$params){
 		//$result=self::getData($params);
		//$jsonString= json_encode($result);
	$jsonString = '{
    "bpid": null,
    "rccy_code": "USD",
    "bp": {
        "ID": "1",
        "BP_ID": "35921",
        "CLOSE_DATE": "9999-12-31 00:00:00",
        "CONTRACT_TYPE": "type",
        "EFFECTIVE_FROM": "2013-07-01 00:00:00",
        "EFFECTIVE_TO": "9999-12-31 00:00:00",
        "RCCY_CODE": "USD",
        "FRONTSALES_CODE": "106"
    },
    "assetsposition": [
        {
            "ID": "4160467",
            "ACCRUED_INT_ASS_CCY_OTH": "0.0000000000",
            "ACCRUED_INT_ASS_CCY_SEC": "0.0000000000",
            "ACCRUED_INT_REF_CCY_OTH": "0.00",
            "ACCRUED_INT_REF_CCY_SEC": "0.00",
            "AS_AT_DATE": "2014-04-01 00:00:00",
            "ASSET_DISPLAY_NAME": "Virtual Exposure USD",
            "AVG_PRICE_IN_ASSET_CCY": null,
            "BP_ID": "36826",
            "BUY_AMT": null,
            "BUY_CURR": null,
            "DW_CONT_ID": "11",
            "CONT_NAME": "FX Margin Portfolio",
            "OUTSTANDING_DAYS": null,
            "EXCHANGE_RATE": null,
            "BUY_SELL": null,
            "INTR_AMT": null,
            "INTR_RATE": null,
            "MAKET_VALUE_ASS_CCY": "0.0000000000",
            "MAKET_VALUE_REF_CCY": "0.00",
            "MARKET_PRICE_ASSET_CCY": null,
            "MARKET_PRICE_DATE": "2014-04-01 00:00:00",
            "POS_CLOSE_DATE": "2014-08-01 00:00:00",
            "POS_OPEN_DATE": "2012-01-01 00:00:00",
            "POSITION_CCY": "USD",
            "QUANTITY": "-3300000.0000000000",
            "QUANTITY_BLOCKED": null,
            "REPLACEMENT_CURR": null,
            "REPLACEMENT_VALUE": null,
            "SELL_AMT": null,
            "SELL_CURR": null,
            "TRADE_DATE": null,
            "UNREALIZED_P_L_ASS_CCY": "0.0000000000",
            "VALUE_DATE": null,
            "DW_ASST_ID": "1097005",
            "valueRefCcy": "0.00"
        }
    ],
    "assets": [
        {
            "ID": "1085021",
            "ASSET_CLASS_1": "Cash",
            "ASSET_CLASS_2": "Currency",
            "ASSET_CLASS_3": "Currency",
            "ASSET_COLLAT_SUB": null,
            "ASSET_COLLAT_SUB_TYPE_INTL_ID": null,
            "ASSET_ID": "2660",
            "ASSET_NAME": "USD",
            "ASSET_NAME_LONG": "US Dollar",
            "BLOOMBERG_CODE": null,
            "ASSET_CLASS_1_INTL_ID": "nsl_cas",
            "ASSET_CLASS_1_ORDERBY": "220132",
            "ASSET_CLASS_2_INTL_ID": "nsl_cas_cur",
            "ASSET_CLASS_2_ORDERBY": "220133",
            "ASSET_CLASS_3_INTL_ID": "nsl_cas_cur_cur",
            "ASSET_CLASS_3_ORDERBY": "220134",
            "DENOMINATION_CURR": "USD",
            "EFFECTIVE_FROM": "2013-01-01 00:00:00",
            "EFFECTIVE_TO": "9999-12-31 00:00:00",
            "GENERIC_ASSET_ID": null,
            "ISIN": null,
            "MATURITY_DATE": null,
            "TRADE_UNIT": "0.0100000000"
        }
    ]
}';
		return $jsonString;
	}
	//response array
	public static function getResultArray(&$params){
      $db = JFactory::getDbo();
      $sql = 'SELECT id FROM #__asset ORDER BY ASSET_ID ASC ';
      $db->setQuery($sql);
      $userIds = $db->loadResultArray();
      return $userIds;
	}
		//response Object List
	public static function getObjectList(&$params){
  
	}	

	//select data from database
	public static function getData(&$params){
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
		//get asset position
		//assetClass1
	    $assetClass1 = array("Bonds","Cash","Equities","Funds","Money Market","Others","Structured Products","Credit");
		$query=$dbo->getQuery(true);
		$query->select('ap.*')
		      ->from($dbo->qn("#__asset_position","ap"))
			  ->innerJoin("#__asset as a on ap.dw_asst_id = a.id")
			  ->where("a.asset_class_1 in ('" .implode('\',\'',$assetClass1) .'\')')
		      ->where('ap.bp_id=\'' . $bpId . '\'')->where('ap.as_at_date=\'' . $asofDate->format("Y-m-d") . '\'')
			  ->where('a.ASSET_COLLAT_SUB_TYPE_INTL_ID is null or a.ASSET_COLLAT_SUB_TYPE_INTL_ID in (\'virt_cust\')')
		      ->where('ap.dw_cont_id not in (select c.id from #__containers as c where c.cont_type in (\'sbl_cont\') )')
			  ->order('a.asset_class_1');
		$dbo->setQuery($query,0,1);
		$assetPositions=$dbo->loadObjectList();
		
		//get assets
		$query=$dbo->getQuery(true);
		$query->select('a.*')
		      ->from($dbo->qn('#__asset','a'))
			  ->where("a.asset_class_3 in ('Currency','Virtual Metal')")
			  ->where('a.asset_name=\'' .$refccy.'\'')
		      ->where('a.effective_from<=\''. $asofDate->format('Y-m-d').'\'')
			  ->where('a.effective_to>\''. $asofDate->format('Y-m-d').'\'');
		$dbo->setQuery($query,0,1);
		$unitForBp=$dbo->loadObjectList();
		
		if(!empty($assetPositions)){
			//count value 
			foreach ($assetPositions as $item){
				//add valueRefCcy
				$item->valueRefCcy=$item->MAKET_VALUE_REF_CCY;
			}
			//sort
			//......
	       }
	       $result=array('bpid'=>$bpId,'rccy_code'=>$refccy,'bp'=>$bp,"assetsposition"=>$assetPositions,"assets"=>$unitForBp);
		   $jsonString= json_encode($result);
		   return $jsonString;
	}
}