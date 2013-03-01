<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * カテゴリーを管理するヘルパークラス.
 *
 * @package Helper
 * @author pineray
 * @version $Id:$
 */
class SC_Helper_Category
{
    private $count_check;

    /**
     * コンストラクター
     * 
     * @param boolean $count_check 登録商品数をチェックする場合はtrue
     */
    function __construct($count_check = FALSE)
    {
        $this->count_check = $count_check;
    }

    /**
     * カテゴリー一覧の取得.
     * 
     * @param boolean $cid_to_key 配列のキーをカテゴリーIDにする場合はtrue
     * @return array カテゴリー一覧の配列
     */
    public function getList($cid_to_key = FALSE)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $from = 'dtb_category left join dtb_category_total_count ON dtb_category.category_id = dtb_category_total_count.category_id';
        // 登録商品数のチェック
        if ($this->count_check) {
            $where = 'del_flg = 0 AND product_count > 0';
        } else {
            $where = 'del_flg = 0';
        }
        $objQuery->setOption('ORDER BY rank DESC');
        $arrCategory = $objQuery->select($col, $from, $where);

        if ($cid_to_key) {
            // 配列のキーをカテゴリーIDに
            $arrCategory = SC_Utils_Ex::makeArrayIDToKey('category_id', $arrCategory);
        }
        
        return $arrCategory;
    }

    /**
     * カテゴリーツリーの取得.
     * 
     * @return type
     */
    public function getTree()
    {
        $arrList = $this->getList();
        $arrTree = SC_Utils_Ex::buildTree('category_id', 'parent_category_id', LEVEL_MAX, $arrList);
        return $arrTree;
    }
}
