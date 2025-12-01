<?php
/**
 * Template Name: Email Header
 *
 * @package Cart Abandonment Recovery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
ob_start();
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html__( 'Cart Abandonment Recovery Weekly Report', 'woo-cart-abandonment-recovery' ); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link rel="stylesheet" type="text/css" id="newGoogleFontsStatic" href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i">
	<style>
		.mceLabel,.mceText,.mceText h3,.mceText h4,.mcnTextContent,.mcnTextContent h3,.mcnTextContent h4,a,blockquote,body,li,p,table,td{font-family:Lato,Arial,Helvetica,"Helvetica Neue",sans-serif!important}img{-ms-interpolation-mode:bicubic}table,td{mso-table-lspace:0;mso-table-rspace:0}.mceStandardButton,.mceStandardButton td,.mceStandardButton td a{mso-hide:all!important}a,blockquote,li,p,td{mso-line-height-rule:exactly}a,blockquote,body,li,p,table,td{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}.mcnPreviewText{display:none!important}.bodyCell{margin:0 auto;padding:0;width:100%}.ExternalClass,.ExternalClass div,.ExternalClass font,.ExternalClass p,.ExternalClass span,.ExternalClass td{line-height:100%}.ReadMsgBody{width:100%}.ExternalClass{width:100%}a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}body{height:100%;margin:0;padding:0;width:100%;background:#fff}p{margin:0;padding:0}table{border-collapse:collapse}a,p,td{word-break:break-word}h1,h2,h3,h4,h5,h6{display:block;margin:0;padding:0}a img,img{border:0;height:auto;outline:0;text-decoration:none}a[href^=sms],a[href^=tel]{color:inherit;cursor:default;text-decoration:none}li p{margin:0!important}.ProseMirror a{pointer-events:none}.mceColumn .mceButtonLink,.mceColumn-1 .mceButtonLink,.mceColumn-2 .mceButtonLink,.mceColumn-3 .mceButtonLink,.mceColumn-4 .mceButtonLink{min-width:30px}div[contenteditable=true]{outline:0}.ProseMirror h1.empty-node:only-child::before,.ProseMirror h2.empty-node:only-child::before,.ProseMirror h3.empty-node:only-child::before,.ProseMirror h4.empty-node:only-child::before{content:'Heading'}.ProseMirror p.empty-node:only-child::before,.ProseMirror:empty::before{content:'Start typing...'}.mceImageBorder{display:inline-block}.mceImageBorder img{border:0!important}#bodyTable,body{background-color:#fef1ec}.mceLabel,.mceText,.mcnTextContent{font-family:Lato,"Helvetica Neue",Helvetica,Arial,sans-serif}.mceLabel,.mceText,.mcnTextContent{color:#4b5563}.mceText h3{margin-bottom:0}.mceText h4{margin-bottom:0}.mceText p{margin-bottom:0}.mceText label{margin-bottom:0}.mceText input{margin-bottom:0}.mceSpacing-12 .mceInput .mceErrorMessage{margin-top:-6px}.mceSpacing-24 .mceInput .mceErrorMessage{margin-top:-12px}.mceInput{background-color:transparent;border:2px solid #d0d0d0;width:60%;color:#4d4d4d;display:block}.mceInput[type=checkbox],.mceInput[type=radio]{float:left;margin-right:12px;display:inline;width:auto!important}.mceLabel>.mceInput{margin-bottom:0;margin-top:2px}.mceLabel{display:block}.mceText p,.mcnTextContent p{color:#4b5563;font-family:Lato,"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.5;mso-line-height-alt:150%;text-align:center;letter-spacing:0;direction:ltr;margin:0}.mceText h3,.mcnTextContent h3{color:#000;font-family:Lato,"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;line-height:1.5;mso-line-height-alt:150%;text-align:center;letter-spacing:0;direction:ltr}.mceText h4,.mcnTextContent h4{color:#1f2937;font-family:Lato,"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:1.5;mso-line-height-alt:150%;text-align:center;letter-spacing:0;direction:ltr}.mceText a,.mcnTextContent a{color:#f06434;font-style:normal;font-weight:400;text-decoration:underline;direction:ltr}.mceSectionFooter .mceText a,.mceSectionFooter .mcnTextContent a{font-style:normal}#d9 h1,#d9 h2,#d9 h3,#d9 h4,#d9 p,#d9 ul{text-align:center}@media only screen and (max-width:480px){a,blockquote,body,li,p,table,td{-webkit-text-size-adjust:none!important}body{width:100%!important;min-width:100%!important}body.mobile-native{-webkit-user-select:none;user-select:none;transition:transform .2s ease-in;transform-origin:top center}body.mobile-native.selection-allowed .ProseMirror,body.mobile-native.selection-allowed a{user-select:auto;-webkit-user-select:auto}colgroup{display:none}.mceImage img,.mceLogo img,.mceSocialFollowIcon img{height:auto!important}.mceWidthContainer{max-width:660px!important}.mceColumn{display:block!important;width:100%!important}.mceColumn-forceSpan{display:table-cell!important;width:auto!important}.mceColumn-forceSpan .mceButton a{min-width:0!important}.mceReverseStack{display:table;width:100%}.mceColumn-1{display:table-footer-group;width:100%!important}.mceColumn-2{display:block!important;width:100%!important}.mceColumn-3{display:table-header-group;width:100%!important}.mceColumn-4{display:table-caption;width:100%!important}.mceKeepColumns .mceButtonLink{min-width:0}.mceBlockContainer{padding-right:16px!important;padding-left:16px!important}.mceTextBlockContainer{padding-right:16px!important;padding-left:16px!important}.mceBlockContainerE2E{padding-right:0;padding-left:0}.mceSpacing-24{padding-right:16px!important;padding-left:16px!important}.mceImage,.mceLogo{width:100%!important;height:auto!important}.mceText img{max-width:100%!important}.mceFooterSection .mceText,.mceFooterSection .mceText p{font-size:16px!important;line-height:140%!important}.mceText p{margin:0;font-size:16px!important;line-height:1.5!important;mso-line-height-alt:150%}.mceText h3{font-size:20px!important;line-height:1.5!important;mso-line-height-alt:150%}.mceText h4{font-size:16px!important;line-height:1.5!important;mso-line-height-alt:150%}.bodyCell{padding-left:16px!important;padding-right:16px!important}.mceButtonContainer{width:fit-content!important;max-width:fit-content!important}.mceButtonLink{padding:18px 28px!important;font-size:16px!important}#b1{padding:24px!important}#b1 table{margin-left:auto!important;margin-right:auto!important;float:none!important}#b36 .mceTextBlockContainer{padding:12px 24px 10px!important}#gutterContainerId-36{padding:0!important}#b38 table{float:left!important}#b38 .mceButtonContainer{width:100%!important;max-width:348px!important}#b38{padding:5px 16px 12px!important}#b38 .mceButtonLink{padding-top:16px!important;padding-bottom:16px!important}#gutterContainerId-39{padding:0!important}#b39{padding:12px 0 24px!important}}@media only screen and (max-width:640px){.mceClusterLayout td{padding:4px!important}}
	</style>
</head>

<body>
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="background-color: rgb(254, 241, 236);">
		<tbody>
			<tr>
				<td class="bodyCell" align="center" valign="top">
					<table id="root" border="0" cellpadding="0" cellspacing="0" width="100%">
						<?php
							require CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-header.php';

							require CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-content-section.php';

							require CARTFLOWS_CA_DIR . 'modules/weekly-email-report/templates/email-footer.php';
						?>
					</table>
				</td>
			</tr>
		</tbody>
	</table><!-- End -->
</body>

</html>

<?php
return ob_get_clean();
