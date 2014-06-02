<?php

/**
 * Nominations
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once("$IP/extensions/Nominations/Nominations.php");
 *
 * @ingroup Extensions
 * @author Nischay Nahata for WikiWorks.com
 * @version 1.0
 */
 
/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
        die( -1 );
}

$wgExtensionCredits['parser extensions'][] = array(
        'path'           => __FILE__,
        'name'           => 'Nominations',
        'version'        => '0.1',
        'author'         => 'Nischay Nahata', 
);

$wgExtensionMessagesFiles['Nominations'] = __DIR__ . '/Nominations.i18n.php';
$wgAutoloadClasses['ApiApprove'] = __DIR__ . '/Nominations_Api_Approve.php';
$wgAPIModules['apiapprove'] = 'ApiApprove';

function NominateAndNotify($parser) {
    global $wgTitle, $wgScriptPath, $wgUser;

    if (!in_array('bureaucrat', $wgUser->getGroups()) || !in_array('sysop', $wgUser->getGroups())) {
       return '';
    }

    $opts = array();

    for ( $i = 1; $i < func_num_args(); $i++ ) {
    	$opts[] = func_get_arg( $i );
    }

    $options = array();

    foreach ( $opts as $opt ) {
      $pair = explode( '=', $opt, 2 );
      if ( count( $pair ) == 2 ) {
        $name = trim( $pair[0] );
        $value = trim( $pair[1] );
        $options[$name] = $value;
      }
    }

    if ($options['action'] != 'approve' && $options['action'] != 'reject') {
       return 'The first parameter of #NominateAndNotify can only be approve or reject, you gave ' . $options['action'] . '\n';
    }

    $action = $options['action'];
    unset($options['action']);

    $htmlOut = Xml::openElement( 'form',
    	     array(
		'name' => 'nominateAndNotify',
	     	'class' => '',
	     	'action' => "{$wgScriptPath}/api.php",
		'method' => 'post'
	     )
    );

    $htmlOut .= Xml::openElement( 'input',
           array(
               'type' => 'hidden',
               'name' => 'action',
               'value' => 'apiapprove',
           )
    );

    $htmlOut .= Xml::openElement( 'input',
           array(
               'type' => 'hidden',
               'name' => 'approveaction',
               'value' => $action == 'approve' ? 'approve' : 'reject',
           )
    );

    foreach( $options as $key => $value)
    {
      $htmlOut .= Xml::openElement( 'input',
             array(
               'type' => 'hidden',
               'name' => $key,
               'value' => $value,
           )
      );
    }

    $htmlOut .= Xml::openElement( 'input',
           array(
               'type' => 'hidden',
               'name' => 'title',
               'value' => $wgTitle->getFullText(),
           )
    );

    $htmlOut .= Xml::openElement( 'input',
           array(
               'type' => 'submit',
               'value' => $action == 'approve' ? 'Approve and Notify' : 'Reject and Notify',
           )
    );

    $htmlOut .= Xml::closeElement( 'form' );

    return array( $htmlOut, 'noparse' => true, 'isHTML' => true );
}


function NominateAndNotifyInit( Parser &$parser ) {
     $parser->setFunctionHook( 'NominateAndNotify', 'NominateAndNotify' );
     return true;
}


$wgHooks['ParserFirstCallInit'][] = 'NominateAndNotifyInit';

$nominationsSenderEmailAddress = 'sender@example.com';

$wgAvailableRights[] = 'approve-nominations';
$wgGroupPermissions['sysop']['approve-nominations'] = true;
