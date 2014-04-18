<?php

class ProductController extends BaseController {

	public function getProducts()
	{
		$client = App::make('solrClient');
		$query = $client->createSelect();
		$query->createFilterQuery('catalog')->setQuery('solr_system_s:"IDS_Catalog" AND sf_meta_class:"Product"');
		$query->setFields(array('title_s'));
		$query->setStart(0)->setRows(1000);
		
		$facetSet = $query->getFacetSet();
		$facetSet->createFacetField('category')->setField('category_s');
		$facetSet->createFacetField('line')->setField('product_line_s');

		$resultset = $client->execute($query);

		$facets = array();
		$facets['categories'] = $resultset->getFacetSet()->getFacet('category');
		$facets['lines'] = $resultset->getFacetSet()->getFacet('line');

		echo '<pre>';

		echo '<h1>Categories</h1>';
		foreach ($facets['categories'] as $value => $count) {
		    if($count>0){
		    	echo $value . ' [' . $count . ']<br/>';
		    }
		}

		echo '<h1>Lines</h1>';
		foreach ($facets['lines'] as $value => $count) {
		    if($count>0){
		    	echo $value . ' [' . $count . ']<br/>';
		    }
		}

		echo '<h1>Products</h1>';
		foreach ($resultset as $document) {
			echo $document->title_s . '<br/>';
		}


/*
		echo 'NumFound: '.$resultset->getNumFound();
		echo '<pre>';
		foreach ($resultset as $document) {
			var_dump($document);
		}
*/

	}

	public function getProduct($id)
	{
		$client = App::make('solrClient');

		$query = $client->createSelect();

		$query->createFilterQuery('catalog')->setQuery('solr_system_s:"IDS_Catalog" AND sf_meta_class:"Product"');
		//$query->createFilterQuery('id')->setQuery('sf_meta_id:70');

		$resultset = $client->execute($query);
		echo 'NumFound: '.$resultset->getNumFound();
		echo '<pre>';
		foreach ($resultset as $document) {
			var_dump($document);
		}
	}

}