<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Magefan\Blog\Model;

/**
 * Category management model
 */
class CategoryManagement extends AbstractManagement
{
    /**
     * @var \Magefan\Blog\Model\CategoryFactory
     */
    protected $_itemFactory;

    /**
     * Initialize dependencies.
     *
     * @param \Magefan\Blog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
        \Magefan\Blog\Model\CategoryFactory $categoryFactory
    ) {
        $this->_itemFactory = $categoryFactory;
    }
    
     /**
      * Retrieve list of category by page type, term, store, etc
      *
      * @param  string $type
      * @param  string $term
      * @param  int $storeId
      * @param  int $page
      * @param  int $limit
      * @return string
      */
    public function getList($type, $term, $storeId, $page, $limit)
    {
        try {
            $collection = $this->_itemFactory->create()->getCollection();
            $collection
                ->addActiveFilter()
                ->addStoreFilter($storeId)
                ->setCurPage($page)
                ->setPageSize($limit);

            $type = strtolower($type);

            switch ($type) {
                case 'search':
                    $collection->addSearchFilter($term);
                    break;
            }

            $categories = [];
            foreach ($collection as $item) {
                $item->initDinamicData();
                $categories[] = $item->getData();
            }

            $result = [
                'categories' => $categories,
                'total_number' => $collection->getSize(),
                'current_page' => $collection->getCurPage(),
                'last_page' => $collection->getLastPageNumber(),
            ];

            return json_encode($result);
        } catch (\Exception $e) {
            return false;
        }
    }
}
