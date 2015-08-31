<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//breadcrumb
$breadcrumb->add($locale->get('title_groups'), 'admin.php?p='.$module_name.'&act=groups');

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('groups_title_add'), 'mod' => $locale->get('groups_title_mod'));

    include_once $include_dir."/function.newsletter.php";
    require_once 'HTML/QuickForm.php';
    require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

    $form =& new HTML_QuickForm('frm_newsletter_groups', 'post', 'admin.php?p='.$module_name);

    $form->setRequiredNote($locale->get('groups_form_required_note'));

    $form->addElement('header', 'groups', $locale->get('title_groups'));
    $form->addElement('hidden', 'act',    $page);

    //csoport neve
    $form->addElement('text', 'name', $locale->get('groups_field_name'), 'maxlength="128"');

    $form->addElement('submit', 'submit', $locale->get('groups_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('groups_form_reset'),  'class="reset"');

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('name', $locale->get('groups_error_required_name'), 'required');

    //userek listaja
    $query = "
        SELECT u.newsletter_user_id AS uid, u.name AS uname
        FROM iShark_Newsletter_Users u
        WHERE u.is_deleted = '0' AND is_active = '1'
        ORDER BY u.name
    ";
    $result =& $mdb2->query($query);
    $select =& $form->addElement('select', 'srcList', $locale->get('groups_field_users'), $result->fetchAll('', $rekey = true), 'id="srcList"');
    $select->setSize(10);
    $select->setMultiple(true);

    /**
     * ha uj csoportot adunk hozza
     */
    if ($sub_act == "add") {
        $form->addElement('hidden', 'sub_act', $sub_act);

        $form->addFormRule('check_addNewslettergroup');
        if ($form->validate()) {
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
            $name   = $form->getSubmitValue('name');

            $newsletter_group_id = $mdb2->extended->getBeforeID('iShark_Newsletter_Groups', 'newsletter_group_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Newsletter_Groups
                (newsletter_group_id, group_name, is_deleted)
                VALUES
                ($newsletter_group_id, '$name', '0')
            ";
            $mdb2->exec($query);
            $last_grp_id = $mdb2->extended->getAfterID($newsletter_group_id, 'iShark_Newsletter_Groups', 'newsletter_group_id');

            // Naplózás
            logger($page.'_'.$sub_act);

            //felvisszuk a csoporthoz a termekeket
            if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
                foreach ($_POST['destList0'] as $key => $value) {
                    //beszurjuk a csoportba a termeket
                    $query = "
                        INSERT INTO iShark_Newsletter_Groups_Users
                        (newsletter_user_id, newsletter_group_id)
                        VALUES
                        ('$value', $last_grp_id)
                    ";
                    $mdb2->exec($query);
                }
            }

            $form->freeze();

            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }

    } //csoport hozzadas vege

    /**
     * ha modositunk egy csoportot
     */
    if ($sub_act == "mod") {
        $gid = intval($_REQUEST['gid']);

        $form->addElement('hidden', 'sub_act', $sub_act);
        $form->addElement('hidden', 'gid',     $gid);

        //lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
        $query = "
            SELECT *
            FROM iShark_Newsletter_Groups
            WHERE newsletter_group_id = $gid
        ";
        $result = $mdb2->query($query);
        if ($result->numRows() > 0) {
            while ($row = $result->fetchRow()) {
                $form->setDefaults(array(
                    'name' => $row['group_name']
                    )
                );

                //lekerdezzuk a mar felvitt usereket
                $query = "
                    SELECT u.newsletter_user_id AS uid, u.name AS uname
                    FROM iShark_Newsletter_Groups_Users gu, iShark_Newsletter_Users u
                    WHERE newsletter_group_id = $gid AND u.is_deleted = '0' AND u.newsletter_user_id = gu.newsletter_user_id
                ";
                $result =& $mdb2->query($query);
                $user_array = array();
                while ($row = $result->fetchRow()) {
                    $user_array[$row['uid']] = $row['uname'];
                }
                $tpl->assign('destList', $user_array);
            }
        } else {
            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }

        $form->addFormRule('check_modNewslettergroup');
        if ($form->validate()) {
            //SQL Escape szûrés
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

            $group_name = $form->getSubmitValue('name');

            $query = "
                UPDATE iShark_Newsletter_Groups
                SET group_name = '$group_name'
                WHERE newsletter_group_id = $gid
            ";
            $mdb2->exec($query);

            //kitoroljuk az eddig ehhez a csoporthoz tartozo usereket
            $query = "
                SELECT gu.newsletter_user_id AS guserid, gu.newsletter_group_id AS ggrpid, nu.newsletter_user_id AS nuserid , nu.is_deleted
                FROM iShark_Newsletter_Groups_Users AS gu
                LEFT JOIN iShark_Newsletter_Users AS nu ON gu.newsletter_user_id = nu.newsletter_user_id
                WHERE nu.is_deleted = '0' AND gu.newsletter_group_id = $gid
            ";
            $result = $mdb2->query($query);
            while($row = $result->fetchRow()){
                $query = "
                    DELETE FROM iShark_Newsletter_Groups_Users
                    WHERE newsletter_group_id = $gid AND newsletter_user_id = ".$row['guserid']."
                ";
                $mdb2->exec($query);
            }

            //felvisszuk a csoporthoz a termekeket
            if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
                foreach ($_POST['destList0'] as $key => $value) {
                    //beszurjuk a csoportba a termeket
                    $query = "
                        INSERT INTO iShark_Newsletter_Groups_Users
                        (newsletter_group_id, newsletter_user_id)
                        VALUES
                        ($gid, '$value')
                    ";
                    $mdb2->exec($query);
                    }
            }

            logger($page.'_'.$sub_act);

            $form->freeze();

            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }
    } //csoport modositas vege

    $renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
    $form->accept($renderer);

    //breadcrumb
    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('lang_title', $titles[$sub_act]);

    //megadjuk a tpl file nevet, amit atadunk az admin.php-nek
    $acttpl = 'newsletter_groups_add';
}

/**
 * ha torlunk egy csoportot
 */
if ($sub_act == "del") {
    $gid = intval($_GET['gid']);

    $query = "
        UPDATE iShark_Newsletter_Groups
        SET is_deleted = '1'
        WHERE newsletter_group_id = $gid
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
    //lekerdezzuk az adatbazisbol a csoportok listajat
    $query = "
        SELECT g.newsletter_group_id AS gid, g.group_name AS gname, g.is_deleted AS gdel
        FROM iShark_Newsletter_Groups g
        WHERE g.is_deleted = '0'
        ORDER BY g.newsletter_group_id
    ";
    $result = $mdb2->query($query);
    //ha ures a lista, akkor uzenet
    if ($result->numRows() == 0) {
        $tpl->assign('empty_list', $locale->get('warning_list_empty'));
    } else {
        require_once 'Pager/Pager.php';
        $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

        foreach ($paged_data['data'] as $key => $adat) {
            $userlist = "";
            $query = "
                SELECT u.name AS uname, u.email as email
                FROM iShark_Newsletter_Users u, iShark_Newsletter_Groups_Users gu
                WHERE u.is_deleted = 0 AND gu.newsletter_group_id = '".$adat['gid']."' AND gu.newsletter_user_id = u.newsletter_user_id
            ";
            $result = $mdb2->query($query);
            while ($row = $result->fetchRow())
            {
                $userlist .= $row['uname']." - ".$row['email']."<br />";
            }
            $adat['userlist'] = $userlist;
            $data[] = $adat;
        }

        //atadjuk a smarty-nak a kiirando cuccokat
        $tpl->assign('page_data', $data);
        $tpl->assign('page_list', $paged_data['links']);
    }

    $add_new = array(
        array(
            'link'  => 'admin.php?p='.$module_name.'&act='.$page.'&amp;sub_act=add',
            'title' => $locale->get('act_group_add'),
            'pic'   => 'add.jpg'
        )
    );

    $tpl->assign('add_new',    $add_new);
    $tpl->assign('lang_title', $locale->get('groups_title_list'));

    //megadjuk a tpl file nevet, amit atadunk az admin.php-nek
    $acttpl = 'newsletter_grouplist';

}

?>