<?php
	require_once(dirname(__FILE__).'/init.php.tpl');

	$curDir = $argv[1];
	
	define('CLASSES_DIR', $curDir.'/classes');
	define('META_FILE', $curDir.'/config/meta.xml');
	
	$builders = array(
		'AutoBusinessClass' => META_BUILDER_DIR.'/phpTemplates/autoBusinessClass.php',
		'BusinessClass' => META_BUILDER_DIR.'/phpTemplates/businessClass.php',
		'AutoDAClass' => META_BUILDER_DIR.'/phpTemplates/autoDAClass.php',
		'DAClass' => META_BUILDER_DIR.'/phpTemplates/DAClass.php'
	);
	
	$targetFiles = array(
		'AutoBusinessClass' => array(CLASSES_DIR.'/business/auto/Auto', '.class.php'),
		'BusinessClass' => array(CLASSES_DIR.'/business/', '.class.php'),
		'AutoDAClass' => array(CLASSES_DIR.'/da/auto/Auto', 'DA.class.php'),
		'DAClass' => array(CLASSES_DIR.'/da/', 'DA.class.php')
	);
	
	$protectedFiles = array(
		'BusinessClass' => true,
		'DAClass' => true
	);
	
	foreach ($builders as $builderName => $builderFile) {
		${$builderName} =
			PhpView::create()->
			loadLayout(File::create()->setPath($builderFile));
	}
	
	$meta = ExtendedDomDocument::create();
	$meta->load(META_FILE);
	
	preConfigure($meta);
	
	$classNode = $meta->createElement('className', null);
	
	$meta->getDocumentElement()->insertBefore(
		$classNode,
		$meta->getDocumentElement()->childNodes->item(0)
	);
	
	$model = Model::create()->set('meta', $meta);
	
	foreach ($meta->getDocumentElement()->childNodes as $node) {
		if (
			$node->nodeType !== XML_ELEMENT_NODE
			|| $node->nodeName == 'className'
			|| (
				$node->getAttribute('type')
				&& $node->getAttribute('type') != 'Identifier'
			)
		)
			continue;

		$classNode->nodeValue = $node->nodeName;
		
		foreach ($builders as $builderName => $builderFile) {
			$file =
				File::create()->setPath(
					join($node->nodeName, $targetFiles[$builderName])
				);
			
			if (!isset($protectedFiles[$builderName]) || !$file->isExists())
				$file->setContent(${$builderName}->transform($model));
		}
	}
	
	function preConfigure(ExtendedDomDocument $meta)
	{
		foreach ($meta->getDocumentElement()->childNodes as $node) {
			if ($node->nodeType !== XML_ELEMENT_NODE)
				continue;
	
			$propertiesNode = $node->getElementsByTagName('properties')->item(0);
	
			if (!$propertiesNode)
				continue;
			
			foreach ($propertiesNode->childNodes as $propertyNode) {
				if ($propertyNode->nodeType !== XML_ELEMENT_NODE)
					continue;
				
				$propertyNode->setAttribute(
					'upperName',
					StringUtils::upperKeyFirstAlpha($propertyNode->nodeName)
				);
				
				$propertyNode->setAttribute(
					'downSeparatedName',
					StringUtils::separateByUpperKey($propertyNode->nodeName)
				);
				
				if ($propertyNode->getAttribute('class')) {
					$relationClass = 
						$meta->getNode($propertyNode->getAttribute('class'));
					
					Assert::isNotNull($relationClass, $propertyNode->getAttribute('class'));
					
					$relationClassType = 
						$relationClass->getAttribute('type')
							? $relationClass->getAttribute('type')
							: 'Identifier';
					
					$propertyNode->setAttribute('classType', $relationClassType);	
					
					if ($relationClassType != 'Identifier')
						continue;
					
					$relationNode = $meta->createElement($propertyNode->nodeName.'Id');
	
					foreach ($propertyNode->attributes as $attrName => $attrValue) {
						if ($attrName != 'class' && $attrName != 'classType')
							$relationNode->setAttribute($attrName, $attrValue->value);
					}
					
					$relationNode->setAttribute(
						'upperName',
						StringUtils::upperKeyFirstAlpha($relationNode->nodeName)
					);
					
					$relationNode->setAttribute(
						'downSeparatedName',
						StringUtils::separateByUpperKey($relationNode->nodeName)
					);
					
					$propertiesNode->insertBefore($relationNode, $propertyNode);
				}
			}
		}
	}
?>