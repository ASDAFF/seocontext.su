<?php

require_once('style-switcher-conf.php');

/**
 * ����������� ���������� ���������� ������� �������� true �� ����������� ����� header.php
 *
 * $iFortisSkipHeading          - ������ ������� � ����� h1 � �������� ��������
 * $iFortisSkipContentContainer - ������ ���� ��������� ��������
 * $iFortisSkipSidebar          - ������ ������ �������
 */


if ( ! empty($aFortisConfig) && ! empty($aFortisConfig['sidebar_align']) && $aFortisConfig['sidebar_align'] == 'right') {
    $sFortisColSidebar = 'col-sidebar col-sidebar-right col-md-3 col-md-push-9 hidden-sm hidden-xs';
    $sFortisColContent = 'col-content col-content-right col-md-9 col-md-pull-3 col-sm-12 col-xs-12';
} else {
    $sFortisColSidebar = 'col-sidebar col-sidebar-left col-md-3 hidden-sm hidden-xs';
    $sFortisColContent = 'col-content col-content-left col-md-9 col-sm-12 col-xs-12';
}

if (isset($iFortisSkipSidebar) && !is_null($iFortisSkipSidebar)) {
  $sFortisColSidebar = 'hidden';
  $sFortisColContent = 'col-xs-12';
}