<?php 
	defined('_JEXEC') or die;
?>

<?php 
	$obj=json_decode($result);
	$meta=$obj->meta;
	$data=$obj->data;
	$portfolios=$data->result->portfolios;
?>
<div class="mod_portfolio_list">
	 <?php if(!empty($meta->errorType)):?>
	 	<div class="content">The request fial,ErrorMessage:<?php echo $meta->errorMessage;?></div>
	 <?php else:?>
	 	 <div class="row-striped">

	 	<?php foreach($portfolios as $item_p):?>
	 	<div>
	 		 <div class="row-fluid">
		 	 	<span msgid="publicAccountNumber">Account Number:</span><?php echo $item_p->bpKey;?><span style="margin-left: 20px;"></span>
				<span msgid="publicPortfolioNumber">Portfolio Number:</span><?php echo $item_p->portfolioNo;?><span style="margin-left: 20px;"></span>
				<span msgid="publicNetAssetValues">Net Asset Value:</span> <?php echo $item_p->netAssetValue;?><span id="netAsset${i}"></span>
				<br/>
				<span msgid="publicPortfolioName">Portfolio Name:</span><?php $portfolioNo=$item_p->portfolioNo; $arr=json_decode( json_encode( $data->result->portfolio),true);echo $arr[$portfolioNo]['portfolioName']?> 
	 		 </div>
			<div class="row-fluid">
		 		<div class="span3">
		 			<span msgid="pDetailDescription">Description</span>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailQuantity">Quantity</span>
			    	<p msgid="pDetailQty" class="smallWord">Block Qty</p>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailAvgPrice">Avg. Cost Price</span>
		 		</div>
		 		<div class="span2">
		 			<span msgid="pDetailMarketPrice">Market Price</span>
			        <p msgid="pDetailDDMMYYYY" class="smallWord">DD.MM.YYYY</p>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailValue">Value</span>
		 		</div>
		 		<div class="span2">
		 			<span msgid="pDetailValue">Value</span><?php $arr=json_decode( json_encode( $data->result->portfolio),true);echo '(' . $arr[$portfolioNo]['refCcy']. ')'; ?>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailAlloc">Alloc. %</span>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailUnrealPL">Unreal. P/L</span>
			        <p msgid="pDetailOrginalCurrency" class="smallWord">(Original Currency)</p>
		 		</div>
		 		</div>
			 	<?php 
					//object to arr
					$item_arr=json_decode(json_encode($data->result->assetPosition),true);
					$equities=$item_arr[$portfolioNo]['Equities']['Equities'];			
				?>
				<?php foreach($equities as $item):?>
				<?php $item_obj=json_decode(json_encode($item),true);?>
				<div class="row-fluid">
		 		<div class="span3">
		 		    <span><?php echo $item_obj->ccy?></span>
		 			<span><?php echo $item_obj->description1;?></span>
		 			<span><?php echo $item_obj->description2;?></span>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailQuantity">Quantity</span>
			    	<p msgid="pDetailQty" class="smallWord">Block Qty</p>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailAvgPrice">Avg. Cost Price</span>
		 		</div>
		 		<div class="span2">
		 			<span msgid="pDetailMarketPrice">Market Price</span>
			        <p msgid="pDetailDDMMYYYY" class="smallWord">DD.MM.YYYY</p>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailValue">Value</span>
		 		</div>
		 		<div class="span2">
		 			<span msgid="pDetailValue">Value</span><?php $arr=json_decode( json_encode( $data->result->portfolio),true);echo '(' . $arr[$portfolioNo]['refCcy']. ')'; ?>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailAlloc">Alloc. %</span>
		 		</div>
		 		<div class="span1">
		 			<span msgid="pDetailUnrealPL">Unreal. P/L</span>
			        <p msgid="pDetailOrginalCurrency" class="smallWord">(Original Currency)</p>
		 		</div>
		 		</div>
		 		<?php endforeach;?>
		 	</div>
		 	<?php endforeach;?>
	</div>
	 <?php endif;?>
</div>
