<?php

class Text{
	public static function shortThePost($content, $max_length=200, $tail='...'){
		if (mb_strlen($content, 'utf-8') <= $max_length)
			return $content;
		return mb_substr($content, 0, $max_length, 'utf-8') . $tail;
	}

	/**
	 * [url]https://twitter.com[/url]
	 */
	public static function makeURLLinkFromStr($str, $callback=null){
		$url_pattern = '#\[url\]([^\[\]]+?)\[/url\]#i';
		preg_match_all($url_pattern, $str, $matches);
		if (!isset($matches[1])){
			return $str;
		}
		foreach ($matches[0] as $key => $full_match) {
			$url = $matches[1][$key];
			list($in_or_out, $url) = self::innerLinkOrOuterLink($url);

			// 外部链接进行处理, 比如网址缩短，或者经过一次站内跳转: /go.php?url=xxx
			if ($callback && is_callable($callback) && $in_or_out == 'out'){
				list($href, $url_text) = $callback($url);
			} else {
				$href = $url;
				$url_text = $url;
			}

			$replacement = '<a href="' . $href . '"';
			if ($in_or_out == 'out')
				$replacement .=' target="_blank"';
			$replacement .= '>' . $url_text . '</a>';
			$str = str_replace($full_match, $replacement, $str);
		}
		return $str;
	}

	/**
	 * 判断是内部链接还是外部链接, 如果是外部链接并且缺少http[s]://,则添加
	 */
	public static function innerLinkOrOuterLink($url){
		if (substr($url, 0, 1) == '/'){
			return ['in', $url];
		}
		if (!preg_match('#^http[s]?://.*$#i', $url)){
			$url = 'http://' . $url;
		}
		return ['out', $url];
	}

	/**
	 * 抽取出 [img] 标签
	 * @return  array [ [img1, img2], origin_str, after_replace_str]
	 */
	public static function detectIMGTag($str){
		$allow_endswith = ['.jpg', '.png', '.gif', '.jpeg'];

		$img_pattern = '#\[img\]([^\[\]]+?)\[/img\]#i';
		preg_match_all($img_pattern, $str, $matches);
		if (!isset($matches[1]) || empty($matches[1])){
			return [[], $str, $str];
		}
		// 不取出无效 img 链接
		$match_img = [];
		$after_replace_str = $str;
		foreach ($matches[0] as $k => $full_match) {
			$img_url = $matches[1][$k];
			foreach ($allow_endswith as $allow_ext) {
				if (String::endswith($img_url, $allow_ext)){
					$match_img[] = $img_url;
					$after_replace_str = str_replace($full_match, '', $after_replace_str);
					break;
				}
			}
		}
		return [$match_img, $str, $after_replace_str];
	}

}
