<?php
	call_user_func(function ($config) {
		$data=null;
		add_shortcode('custom-template',function ($atts, $content) use (&$data,$config) {
			$config = array_merge(Timber::get_context(),$config);
			$data=array();
			//	Content is ignored
		  $atts['content'] = do_shortcode(strip_tags($content,'<div><a><em><bold><strong><i><li><ul><img><p><h1><blockquote><h2><h3>'));
			$retr = '<script>console.error("[custom-template] shortcode Template was not defined");</script>';
			if (isset($atts['template'])) {
				$t=$atts['template'];
				unset($atts['template']);
				$data = array_merge($data, $atts);
				$atts['data']=$data;

				$data=null;
				try {
					ob_start();
					Timber::render($t,array_merge($atts,$config));
					$retr=ob_get_contents();
					ob_end_clean();
				} catch (Twig_Error_Loader $e){
					$retr = '<script>console.error("Error Loading twig template '. $t . ' ' .str_replace('"',"'",$e->getMessage()) .'")</script>';
				}
			}
			return $retr;
		});
		add_shortcode('custom-item',function ($atts, $content) use (&$data) {
			if (is_null($data)) return '';
			$atts['content']= do_shortcode($content);
			$data[]=$atts;
			return '';
		});
	},array('config'=>$get_config()) );
?>
