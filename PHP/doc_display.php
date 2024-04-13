<?php

	function doc_display($doc, $ftype)
	{
		if($ftype == 'pdf')
		{
			// state that following data will be pdf document

			header("Content-Type: application/pdf");
			
			/*
				"Content-Disposition: inline" means that the pdf needed to be displayed in the browser not downloaded
				we also give the pdf generated a new name, which will be used as the name of the pdf if it's downloaded
			*/

			header("Content-Disposition: inline; filename=support_doc.pdf");

			// if your browser doesn't display pdf, directly downloads it, you need to turn on display pdf feature
		}
		else
		{
			// state that following data will be image

			header("Content-type: image/$ftype");
		}

		/*
			return binary data (file content extracted from img table stored in $row['image']
			as string) of the content (image/pdf)
		*/

		echo $doc;
	}

?>