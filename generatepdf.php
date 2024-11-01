<?php
/**
 * Thanks to Aleksander Stacherski for as-pdf plugin
 */
//echo "test1";
    //require files
    require_once($_SERVER['DOCUMENT_ROOT']."/wp-config.php");
    require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');

    // Custom parameter in URL
    // TITLE    title
    if(isset($_GET['title'])) {
        $title = $_GET['title'];
    } else {
        $title = ($_POST['title_handbook']!="")?$_POST['title_handbook']:'Handbook';
    }
	

    // PICTURE     picture
    /*if(isset($_GET['picture'])) {
        $image = $_GET['picture'];
        //$urlimg = $_GET['title'];

        $outPath = (string)time();
        $outPath .= $outPath.'.jpg';
        $in = fopen($image, "rb");
        $out = fopen('../../uploads/'.$outPath, "wb");
        $uploadfile = '../../uploads/'.$outPath;
        while ($chunk = fread($in,8192))
        {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    } else {
        // Upload section
        // $uploaddir = get_bloginfo("wpurl") .'/wp-content/uploads/';
        $image = $_FILES['uploadfile']['name'];
        if($image) {
            $uploaddir = '../../uploads/';
            $uploadfile = $uploaddir . basename($image);

            if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
                echo "Problem with uploaded file";
                echo "<pre>";
                print_r($_FILES);
                echo "</pre>";
            }
        }
    }*/

    // get id session handbook  4DO: Add verification
    $objPost1 = array();
    $objPost1 = $_SESSION['handbook'];

    //$objTcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
    $objTcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $objTcpdf->SetCreator(PDF_CREATOR);
    $objTcpdf->SetAuthor( $strAuthor );
    $objTcpdf->SetTitle( get_option('blogname') );

    // set default header data
    $objTcpdf->SetHeaderData(null, null, get_option('blogname') . ' | '.$title , 'Copyright ' . $strAuthor . "\n" . $strPermalink );

    // set header and footer fonts
    $objTcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $objTcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    //set margins
    $objTcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $objTcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $objTcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //set auto page breaks
    $objTcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //set image scale factor
    $objTcpdf->setImageScale('5');

    //set some language-dependent strings
    $objTcpdf->setLanguageArray($l);

    //initialize document
    $objTcpdf->AliasNbPages();

    // add a page
    $objTcpdf->AddPage('P','LETTER');

    // set font
    $objTcpdf->SetFont( PDF_FONT_NAME_MAIN, '', get_option( 'as_pdf_main_font_size' ) );

    // set JPEG quality
    $objTcpdf->setJPEGQuality(75);

    // Path Image
    //$strHtml = '<img src="'.$uploadfile.'" style="float:right" />';
    /*if($image) {
        $objTcpdf->Image($uploadfile, 178, 20, '', '', 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);
    }*/

    // Loop in posts
    foreach($objPost1 as $key => $value) {
        $objPost = get_post( $value );

        $strHtml .= '<h2>' . $objPost->post_title . '</h2>' . wpautop( $objPost->post_content, true );
    }
    // output the HTML content
    $objTcpdf->writeHTML( $strHtml , true, false, true, false, '');

    //output PDF document
    $objTcpdf->Output( get_option('blogname') . '-' .$title . '.pdf', 'I' );

?>
