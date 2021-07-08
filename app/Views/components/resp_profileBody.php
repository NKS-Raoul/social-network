<div class="simple_flex w100 resp_profile_body" style="align-items: flex-start !important;">
    <?php require_once($view . 'components/infosUser.php'); ?>



    <div class="w75 column resp_profile_subject">

        <?php if (!isset($userProfile) || (isset($userProfile) && $userProfile[0]->user_id == $user->user_id)) :
            require_once($view . 'components/addPublication.php');
        endif;
        ?>

        <?php require_once($view . 'components/publications.php'); ?>

    </div>
</div>