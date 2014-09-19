<?php
defined('_JEXEC') or die;
abstract class ModPortfolioList
{
	//response json
	public static function getResult(&$params){
 		$result=self::getPortfolioList($params);
	$jsonString='{
    "meta": {
        "errorType": null,
        "errorMessage": null,
        "code": "200",
        "errorId": null
    },
    "pagination": null,
    "data": {
        "result": {
            "assetPosition": {
                "12345678.001": {
                    "Equities": {
                        "Equities": [
                            {
                                "ccy": "HKD",
                                "description1": "FU JI FOOD & CATERING SERVIC (1175 HK)",
                                "description2": "KYG3685B1124",
                                "nominalAmt": "11,959",
                                "type": null,
                                "qty": "11,959",
                                "qtyBlocked": "0",
                                "avgCostPrice": "0.00",
                                "maturityDate": null,
                                "purchasePrice": "0.00",
                                "marketPrice": "2.24",
                                "marketPriceDate": "01.04.2014",
                                "value": "26,788.16",
                                "valueAccrInt": "0.00",
                                "refValue": "3,608.47",
                                "refValueAccrInt": "0.00",
                                "allocation": "2.37",
                                "unrealPL": "26,788.16",
                                "principal": "11,959.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "JPY",
                                "description1": "INTERNET INITIATIVE JAPAN (3774 JP)",
                                "description2": "JP3152820001",
                                "nominalAmt": "4,300",
                                "type": null,
                                "qty": "4,300",
                                "qtyBlocked": "0",
                                "avgCostPrice": "3,346.00",
                                "maturityDate": null,
                                "purchasePrice": "3,346.00",
                                "marketPrice": "3,330.00",
                                "marketPriceDate": "01.04.2014",
                                "value": "14,319,000",
                                "valueAccrInt": "0",
                                "refValue": "148,366.82",
                                "refValueAccrInt": "0.00",
                                "allocation": "97.63",
                                "unrealPL": "-68,800",
                                "principal": "4,300",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            }
                        ]
                    }
                },
                "12345678.002": {
                    "Equities": {
                        "Equities": [
                            {
                                "ccy": "HKD",
                                "description1": "HANG SENG BANK LTD (11 HK)",
                                "description2": "HK0011000095",
                                "nominalAmt": "17,280",
                                "type": null,
                                "qty": "17,280",
                                "qtyBlocked": "0",
                                "avgCostPrice": "101.25",
                                "maturityDate": null,
                                "purchasePrice": "101.25",
                                "marketPrice": "119.80",
                                "marketPriceDate": "01.04.2014",
                                "value": "2,070,144.00",
                                "valueAccrInt": "0.00",
                                "refValue": "264,488.66",
                                "refValueAccrInt": "0.00",
                                "allocation": "34.30",
                                "unrealPL": "320,490.42",
                                "principal": "17,280.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "HKD",
                                "description1": "POWER ASSETS HOLDINGS LTD (6 HK)",
                                "description2": "HK0006000050",
                                "nominalAmt": "1,000",
                                "type": null,
                                "qty": "1,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "62.99",
                                "maturityDate": null,
                                "purchasePrice": "62.99",
                                "marketPrice": "71.25",
                                "marketPriceDate": "01.04.2014",
                                "value": "71,250.00",
                                "valueAccrInt": "0.00",
                                "refValue": "8,981.19",
                                "refValueAccrInt": "0.00",
                                "allocation": "1.16",
                                "unrealPL": "8,263.30",
                                "principal": "1,000.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "JPY",
                                "description1": "DAIWA HOUSE INDUSTRY (1925 JP)",
                                "description2": "JP3505000004",
                                "nominalAmt": "27,000",
                                "type": null,
                                "qty": "27,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "1,895.15",
                                "maturityDate": null,
                                "purchasePrice": "1,895.15",
                                "marketPrice": "1,817.00",
                                "marketPriceDate": "01.04.2014",
                                "value": "49,059,000",
                                "valueAccrInt": "0",
                                "refValue": "497,684.43",
                                "refValueAccrInt": "0.00",
                                "allocation": "64.54",
                                "unrealPL": "-2,110,078",
                                "principal": "27,000",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            }
                        ]
                    }
                },
                "12345678.003": {
                    "Equities": {
                        "Equities": [
                            {
                                "ccy": "HKD",
                                "description1": "BOC HONG KONG HOLDINGS LTD (2388 HK)",
                                "description2": "HK2388011192",
                                "nominalAmt": "12,000",
                                "type": null,
                                "qty": "12,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "23.24",
                                "maturityDate": null,
                                "purchasePrice": "23.24",
                                "marketPrice": "25.00",
                                "marketPriceDate": "01.04.2014",
                                "value": "300,000.00",
                                "valueAccrInt": "0.00",
                                "refValue": "37,678.43",
                                "refValueAccrInt": "0.00",
                                "allocation": "4.61",
                                "unrealPL": "21,178.80",
                                "principal": "12,000.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "HKD",
                                "description1": "HUANENG RENEWABLES CORP-H (958 HK)",
                                "description2": "CNE100000WS1",
                                "nominalAmt": "200,000",
                                "type": null,
                                "qty": "200,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "2.53",
                                "maturityDate": null,
                                "purchasePrice": "2.53",
                                "marketPrice": "2.82",
                                "marketPriceDate": "01.04.2014",
                                "value": "564,000.00",
                                "valueAccrInt": "0.00",
                                "refValue": "70,921.07",
                                "refValueAccrInt": "0.00",
                                "allocation": "8.69",
                                "unrealPL": "58,960.00",
                                "principal": "200,000.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "HKD",
                                "description1": "INNER MONGOLIA YITAI COAL -H (3948 HK)",
                                "description2": "CNE100001FW6",
                                "nominalAmt": "75,000",
                                "type": null,
                                "qty": "75,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "43.65",
                                "maturityDate": null,
                                "purchasePrice": "43.65",
                                "marketPrice": "15.10",
                                "marketPriceDate": "01.04.2014",
                                "value": "1,132,500.00",
                                "valueAccrInt": "0.00",
                                "refValue": "150,868.46",
                                "refValueAccrInt": "0.00",
                                "allocation": "18.48",
                                "unrealPL": "-2,141,133.00",
                                "principal": "75,000.00",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            },
                            {
                                "ccy": "JPY",
                                "description1": "DENTSU INC. (4324 JP)",
                                "description2": "JP3551520004",
                                "nominalAmt": "14,000",
                                "type": null,
                                "qty": "14,000",
                                "qtyBlocked": "0",
                                "avgCostPrice": "3,222.17",
                                "maturityDate": null,
                                "purchasePrice": "3,222.17",
                                "marketPrice": "3,195.00",
                                "marketPriceDate": "01.04.2014",
                                "value": "44,730,000",
                                "valueAccrInt": "0",
                                "refValue": "447,988.58",
                                "refValueAccrInt": "0.00",
                                "allocation": "54.86",
                                "unrealPL": "-380,373",
                                "principal": "14,000",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            }
                        ],
                        "Real Estate Investment Trust": [
                            {
                                "ccy": "JPY",
                                "description1": "NIPPON BUILDING FUND INC. (8951 JP)",
                                "description2": "JP3027670003",
                                "nominalAmt": "10",
                                "type": null,
                                "qty": "10",
                                "qtyBlocked": "0",
                                "avgCostPrice": "1,148,313.60",
                                "maturityDate": null,
                                "purchasePrice": "1,148,313.60",
                                "marketPrice": "1,113,000.00",
                                "marketPriceDate": "01.04.2014",
                                "value": "11,130,000",
                                "valueAccrInt": "0",
                                "refValue": "109,083.48",
                                "refValueAccrInt": "0.00",
                                "allocation": "13.36",
                                "unrealPL": "-353,136",
                                "principal": "10",
                                "periodDate": "01.01.2012 - 01.08.2014",
                                "interestRate": null,
                                "interestAmt": null,
                                "onOfDays": null,
                                "buyCurrency": null,
                                "sellCurrency": null,
                                "fxTxType": null,
                                "buyAmt": null,
                                "sellAmt": null,
                                "exRate": null,
                                "tradeDate": null,
                                "valueDate": null,
                                "superScript": null
                            }
                        ]
                    }
                }
            },
            "portfolios": [
                {
                    "bpKey": "12345678",
                    "portfolioNo": "12345678.001",
                    "netAssetValue": "2,378,790.49",
                    "ccy": "USD"
                },
                {
                    "bpKey": "12345678",
                    "portfolioNo": "12345678.002",
                    "netAssetValue": "1,008,261.72",
                    "ccy": "USD"
                },
                {
                    "bpKey": "12345678",
                    "portfolioNo": "12345678.003",
                    "netAssetValue": "1,663,159.40",
                    "ccy": "USD"
                }
            ],
            "portfolio": {
                "12345678.001": {
                    "portfolioName": "General Portfolio",
                    "ccy": null,
                    "refCcy": "USD",
                    "portfolioIdAndCcy": null,
                    "refValueTotal": "151,975.29",
                    "refValueAccrIntTotal": "0.00",
                    "totalPercentage": "100.00",
                    "hasMetalCcy": false
                },
                "12345678.002": {
                    "portfolioName": "General Portfolio",
                    "ccy": null,
                    "refCcy": "USD",
                    "portfolioIdAndCcy": null,
                    "refValueTotal": "771,154.28",
                    "refValueAccrIntTotal": "0.00",
                    "totalPercentage": "100.00",
                    "hasMetalCcy": false
                },
                "12345678.003": {
                    "portfolioName": "General Portfolio",
                    "ccy": null,
                    "refCcy": "USD",
                    "portfolioIdAndCcy": null,
                    "refValueTotal": "816,540.02",
                    "refValueAccrIntTotal": "0.00",
                    "totalPercentage": "100.00",
                    "hasMetalCcy": false
                }
            },
            "hasEquities": false,
            "hasFixedIncome": false,
            "hasFx": false,
            "hasCommodities": false,
            "hasSpecial": false,
            "hasIPO": false,
            "hasSP": false,
            "hasSBLC": false,
            "noOfIPO": 0,
            "noOfSP": 0,
            "noOfSBLC": 0
        }
    }
}';
		//$jsonString= json_encode($result);
		return $jsonString;
}
public static function getPortfolioList($params)
  {
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
?>
