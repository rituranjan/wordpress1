$sql="SELECT * FROM getMaster;";


    $sql="SELECT CAST(g_master.group_id AS UNSIGNED) AS id, CAST(i_master.item_id AS UNSIGNED) AS item_id, g_master.group_name AS type, 
        i_master.item_value AS Name, 0 AS `value` FROM `tbl_account_item_master` i_master INNER JOIN `tbl_account_group_master` g_master 
        ON i_master.group_id = g_master.group_id UNION ALL SELECT CAST(t.EntityID AS UNSIGNED) AS id, 1 AS item_id, t.`Type`, t.`Name`,
         0 AS `value` FROM `tbl_account_entities` t UNION ALL SELECT CAST(g_master.group_id AS UNSIGNED) AS id,
          CAST(tax.id AS UNSIGNED) AS item_id, g_master.group_name AS type, tax.Name, tax.tax AS `value` FROM tbl_account_tax tax 
          INNER JOIN `tbl_account_group_master` g_master ON tax.group_id = g_master.group_id;"
