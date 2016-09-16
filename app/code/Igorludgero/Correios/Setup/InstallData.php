<?php
/**
 * Created by PhpStorm.
 * User: igorludgeromiura
 * Date: 05/09/16
 * Time: 15:15
 */

namespace Igorludgero\Correios\Setup;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $productTypes = [
            Type::TYPE_SIMPLE,
            Type::TYPE_VIRTUAL,
        ];
        $productTypes = join(',', $productTypes);
        $eavSetup->addAttribute(
            Product::ENTITY,
            'correios_width',
            [
                'type'                    => 'text',
                'label'                   => 'Correios Width',
                'input'                   => 'text',
                'sort_order'              => 50,
                'global'                  => Attribute::SCOPE_WEBSITE,
                'user_defined'            => true,
                'required'                => false,
                'used_in_product_listing' => true,
                'apply_to'                => $productTypes,
                'group'                   => 'General',
                'unique'                  => false,
                'visible_on_front'        => true,
                'searchable'              => false,
                'filterable'              => true,
                'comparable'              => true,
                'visible'                 => true,
                'backend'                 => '',
                'frontend'                => '',
                'class'                   => '',
                'source'                  => '',
                'default'                 => '',
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'correios_height',
            [
                'type'                    => 'text',
                    'label'                   => 'Correios Height',
                'input'                   => 'text',
                'sort_order'              => 51,
                'global'                  => Attribute::SCOPE_WEBSITE,
                'user_defined'            => true,
                'required'                => false,
                'used_in_product_listing' => true,
                'apply_to'                => $productTypes,
                'group'                   => 'General',
                'unique'                  => false,
                'visible_on_front'        => true,
                'searchable'              => false,
                'filterable'              => true,
                'comparable'              => true,
                'visible'                 => true,
                'backend'                 => '',
                'frontend'                => '',
                'class'                   => '',
                'source'                  => '',
                'default'                 => '',
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'correios_depth',
            [
                'type'                    => 'text',
                'label'                   => 'Correios Depth',
                'input'                   => 'text',
                'sort_order'              => 52,
                'global'                  => Attribute::SCOPE_WEBSITE,
                'user_defined'            => true,
                'required'                => false,
                'used_in_product_listing' => true,
                'apply_to'                => $productTypes,
                'group'                   => 'General',
                'unique'                  => false,
                'visible_on_front'        => true,
                'searchable'              => false,
                'filterable'              => true,
                'comparable'              => true,
                'visible'                 => true,
                'backend'                 => '',
                'frontend'                => '',
                'class'                   => '',
                'source'                  => '',
                'default'                 => '',
            ]
        );

        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('igorludgero_cotacoes');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'servico',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Serviço dos Correios'
                )
                ->addColumn(
                    'prazo',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Prazo em dias para a entrega'
                )
                ->addColumn(
                    'peso',
                    Table::TYPE_FLOAT,
                    null,
                    ['nullable' => false],
                    'Peso do pacote'
                )
                ->addColumn(
                    'valor',
                    Table::TYPE_FLOAT,
                    null,
                    ['nullable' => false],
                    'Valor da entrega'
                )
                ->addColumn(
                    'cep_inicio',
                    Table::TYPE_BIGINT,
                    null,
                    ['nullable' => false],
                    'Primeiro CEP da Faixa'
                )
                ->addColumn(
                    'cep_fim',
                    Table::TYPE_BIGINT,
                    null,
                    ['nullable' => false],
                    'Último CEP da Faixa'
                )
                ->addColumn(
                    'ultimo_update',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Última atualização'
                )
                ->setComment('Tabela para cotação offline do módulo dos Correios do Igorludgero.')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

    }
}