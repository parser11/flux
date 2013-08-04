<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Claus Due <claus@wildside.dk>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * @author Claus Due <claus@wildside.dk>
 * @package Flux
 */
class Tx_Flux_Provider_ProviderTest extends Tx_Flux_Provider_AbstractProviderTest {

	/**
	 * @var array
	 */
	protected $definition = array(
		'name' => 'test',
		'label' => 'Test provider',
		'tableName' => 'tt_content',
		'fieldName' => 'pi_flexform',
		'form' => array(
			'sheets' => array(
				'foo' => array(
					'fields' => array(
						'test' => array(
							'type' => 'Input',
						)
					)
				),
				'bar' => array(
					'fields' => array(
						'test2' => array(
							'type' => 'Input',
						)
					)
				),
			),
			'fields' => array(
				'test3' => array(
					'type' => 'Input',
				)
			),
		),
		'grid' => array(
			'rows' => array(
				'foo' => array(
					'columns' => array(
						'bar' => array(
							'areas' => array(

							)
						)
					)
				)
			)
		)
	);

	/**
	 * @test
	 */
	public function canGetExtensionKey() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$extensionKey = $provider->getExtensionKey($record);
		$this->assertNull($extensionKey);
	}

	/**
	 * @test
	 */
	public function canGetTableName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$tableName = $provider->getTableName($record);
		$this->assertNull($tableName);
	}

	/**
	 * @test
	 */
	public function canSetTableName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->setTableName('test');
		$this->assertSame('test', $provider->getTableName($record));
	}

	/**
	 * @test
	 */
	public function canSetFieldName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->setFieldName('test');
		$this->assertSame('test', $provider->getFieldName($record));
	}

	/**
	 * @test
	 */
	public function canSetExtensionKey() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->setExtensionKey('test');
		$this->assertSame('test', $provider->getExtensionKey($record));
	}

	/**
	 * @test
	 */
	public function canSetTemplateVariables() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$variables = array('test' => 'test');
		$provider->setTemplateVariables($variables);
		$this->assertArrayHasKey('test', $provider->getTemplateVariables($record));
	}

	/**
	 * @test
	 */
	public function canSetTemplatePathAndFilename() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$template = 'test.html';
		$provider->setTemplatePathAndFilename($template);
		$this->assertSame(t3lib_div::getFileAbsFileName($template), $provider->getTemplatePathAndFilename($record));
	}

	/**
	 * @test
	 */
	public function canUseAbsoluteTemplatePathDirectly() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$template = $this->getAbsoluteFixtureTemplatePathAndFilename(self::FIXTURE_TEMPLATE_ABSOLUTELYMINIMAL);
		$provider->setTemplatePathAndFilename($template);
		$this->assertSame($provider->getTemplatePathAndFilename($record), $template);
	}

	/**
	 * @test
	 */
	public function canSetTemplatePaths() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$templatePaths = array(
			'templateRootPath' => 'EXT:flux/Resources/Private/Templates'
		);
		$provider->setTemplatePaths($templatePaths);
		$this->assertSame(Tx_Flux_Utility_Path::translatePath($templatePaths), $provider->getTemplatePaths($record));
	}

	/**
	 * @test
	 */
	public function canSetConfigurationSectionName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$section = 'Custom';
		$provider->setConfigurationSectionName($section);
		$this->assertSame($section, $provider->getConfigurationSectionName($record));
	}

	/**
	 * @test
	 */
	public function canUseInheritanceTree() {
		$provider = $this->getConfigurationProviderInstance();
		$provider->setFieldName('pi_flexform');
		$provider->setTemplatePathAndFilename($this->getAbsoluteFixtureTemplatePathAndFilename(self::FIXTURE_TEMPLATE_FIELD_INPUT));
		$record = $this->getBasicRecord();
		$byPathExists = $this->callInaccessibleMethod($provider, 'getInheritedPropertyValueByDottedPath', $record, 'settings');
		$byDottedPathExists = $this->callInaccessibleMethod($provider, 'getInheritedPropertyValueByDottedPath', $record, 'settings.input');
		$byPathDoesNotExist = $this->callInaccessibleMethod($provider, 'getInheritedPropertyValueByDottedPath', $record, 'void.doesnotexist');
		$this->assertEmpty($byPathDoesNotExist);
		$this->assertEmpty($byPathExists);
		$this->assertEmpty($byDottedPathExists);
	}

	/**
	 * @test
	 */
	public function canOperateArrayMergeFunction() {
		$provider = $this->getConfigurationProviderInstance();
		$array1 = array(
			'foo' => array(
				'bar' => TRUE
			)
		);
		$array2 = array(
			'foo' => array(
				'foo' => TRUE
			)
		);
		$expected = array(
			'foo' => array(
				'bar' => TRUE,
				'foo' => TRUE
			)
		);
		$product = $this->callInaccessibleMethod($provider, 'arrayMergeRecursive', $array1, $array2);
		$this->assertSame($expected, $product);
	}

	/**
	 * @test
	 */
	public function canOperateArrayDiffFunction() {
		$provider = $this->getConfigurationProviderInstance();
		$array1 = array(
			'bar' => TRUE,
			'baz' => TRUE,
			'same' => array(
				'foo' => TRUE
			),
			'foo' => array(
				'bar' => TRUE,
				'foo' => TRUE
			)
		);
		$array2 = array(
			'bar' => TRUE,
			'baz' => FALSE,
			'new' => TRUE,
			'same' => array(
				'foo' => TRUE
			),
			'foo' => array(
				'bar' => TRUE
			)
		);
		$expected = array(
			'baz' => TRUE,
			'foo' => array(
				'foo' => TRUE
			),
			'new' => TRUE,
		);
		$product = $this->callInaccessibleMethod($provider, 'arrayDiffRecursive', $array1, $array2);
		$this->assertSame($expected, $product);
	}

	/**
	 * @test
	 */
	public function canExecuteClearCacheCommand() {
		$provider = $this->getConfigurationProviderInstance();
		$return = $provider->clearCacheCommand(array('all'));
		$this->assertEmpty($return);
	}

	/**
	 * @test
	 */
	public function canGetAndSetListType() {
		$record = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordIsParentAndHasChildren;
		/** @var Tx_Flux_Provider_ProviderInterface $instance */
		$instance = $this->getConfigurationProviderInstance();
		$instance->setExtensionKey('flux');
		$listType = $instance->getListType($record);
		$this->assertNull($listType);
		$instance->setListType('test');
		$this->assertSame('test', $instance->getListType($record));
	}
	/**
	 * @test
	 */
	public function canGetContentObjectType() {
		$instance = $this->getConfigurationProviderInstance();
		$record = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordIsParentAndHasChildren;
		$contentType = $instance->getContentObjectType($record);
		$this->assertNull($contentType);
	}

	/**
	 * @test
	 */
	public function canCreateFormFromDefinitionWithAllSupportedNodes() {
		/** @var Tx_Flux_Provider_ProviderInterface $instance */
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->loadSettings($this->definition);
		$form = $provider->getForm($record);
		$this->assertInstanceOf('Tx_Flux_Form', $form);
	}

	/**
	 * @test
	 */
	public function canCreateGridFromDefinitionWithAllSupportedNodes() {
		/** @var Tx_Flux_Provider_ProviderInterface $instance */
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->loadSettings($this->definition);
		$grid = $provider->getGrid($record);
		$this->assertInstanceOf('Tx_Flux_Form_Container_Grid', $grid);
	}

	/**
	 * @test
	 */
	public function canGetName() {
		$provider = $this->getConfigurationProviderInstance();
		$provider->loadSettings($this->definition);
		$this->assertSame($provider->getName(), $this->definition['name']);
	}

	/**
	 * @test
	 */
	public function canReturnExtensionKey() {
		$record = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordWithoutParentAndWithoutChildren;
		$service = $this->createFluxServiceInstance();
		$provider = $service->resolvePrimaryConfigurationProvider('tt_content', 'pi_flexform', array(), 'flux');
		$this->assertInstanceOf('Tx_Flux_Provider_ContentProvider', $provider);
		$extensionKey = $provider->getExtensionKey($record);
		$this->assertNotEmpty($extensionKey);
		$this->assertRegExp('/[a-z_]+/', $extensionKey);
	}

	/**
	 * @test
	 */
	public function canReturnPathSetByRecordWithoutParentAndWithoutChildren() {
		$row = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordWithoutParentAndWithoutChildren;
		$service = $this->createFluxServiceInstance();
		$provider = $service->resolvePrimaryConfigurationProvider('tt_content', 'pi_flexform', $row);
		$this->assertInstanceOf('Tx_Flux_Provider_ProviderInterface', $provider);
		$paths = $provider->getTemplatePaths($row);
		$this->assertIsArray($paths);
	}

}
