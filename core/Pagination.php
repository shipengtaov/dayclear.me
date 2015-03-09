<?php

class Pagination{
	/**
	 * 分页风格, 默认风格为 1
	 * 1: 上一页 下一页
	 * 2: bootstrap pager 未左右对齐
	 */
	public $styles = array(1, 2);
	public $current_style;  // 使用的风格
	public $html;
	public $current_page;
	public $total_page;
	public $url;
	public $page_param;  // 分页参数, 默认 page

	public $classes;
	public $text;
	public $current_page_class;  // 当前页样式
	public $no_more_page_class;  // 没有上一页或下一页时的样式

	public function __construct(
		$url, 
		$current_page, 
		$total_page, 
		$page_param = 'page', 
		$options = array()
	){
		$this->url = $url;
		$this->current_page = $current_page;
		$this->total_page = $total_page;
		$this->page_param = $page_param;

		$this->classes = isset($options['class']) ? $options['class'] : array();
		$this->classes['current'] = isset($options['class']['current']) ? $options['class']['current'] : 'current';
		$this->classes['no_more'] = isset($options['class']['no_more']) ? $options['class']['no_more'] : 'disabled';
		$this->classes['previous'] = isset($options['class']['previous']) ? $options['class']['previous'] : 'previous';
		$this->classes['next'] = isset($options['class']['next']) ? $options['class']['next'] : 'next';

		// 
		$this->text['previous'] = isset($options['text']['previous']) ? $options['text']['previous'] : '上一页';
		$this->text['next'] = isset($options['text']['next']) ? $options['text']['next'] : '下一页';

		$this->current_style = 1;  // 默认使用风格1
	}

	public function get_html(){
		if( !isset($this->current_style) )
			exit("分页风格未设置");
		$method = "get_html_with_style_" . $this->current_style;
		return $this->$method();
	}

	public function get_html_with_style_1(){
		if( $this->current_page == 1 ){
			$this->html = '<a href="javascript:void(0);" class="' . $this->classes['no_more'] . '">' . $this->text['previous'] . '</a>';
		} else {
			$this->html = '<a href="' . self::replace_page_num_in_url($this->url, $this->page_param, $this->current_page-1) . '">' . $this->text['previous'] . '</a>';
		}
		if( $this->current_page == $this->total_page ){
			$this->html .= '<a href="javascript:void(0);" class="' . $this->classes['no_more'] . '">' . $this->text['next'] . '</a>';
		} else {
			$this->html .= '<a href="' . self::replace_page_num_in_url($this->url, $this->page_param, $this->current_page+1) . '">' . $this->text['next'] . '</a>';
		}
		return $this->html;
	}

	public function get_html_with_style_2(){
		$this->html = '<nav>';
		$this->html .= '<ul class="pager">';
		if( $this->current_page == 1 ){
			$this->html .= '<li class="' . $this->classes['no_more'] . '">';
			$this->html .= '<a href="javascript:void(0);">' . $this->text['previous'] . '</a>';
		} else {
			$this->html .= '<li>';
			$this->html .= '<a href="' . self::replace_page_num_in_url($this->url, $this->page_param, $this->current_page-1) . '">' . $this->text['previous'] . '</a>';
		}
		$this->html .= '</li>';
		$this->html .= $this->current_page . '/' . $this->total_page;
		if( $this->current_page == $this->total_page ){
			$this->html .= '<li class="' . $this->classes['no_more'] . '">';
			$this->html .= '<a href="javascript:void(0);">' . $this->text['next'] . '</a>';
		} else {
			$this->html .= '<li>';
			$this->html .= '<a href="' . self::replace_page_num_in_url($this->url, $this->page_param, $this->current_page+1) . '">' . $this->text['next'] . '</a>';
		}
		$this->html .= '</li>';
		$this->html .= '</ul>';
		$this->html .= '</nav>';
		return $this->html;
	}

	static public function replace_page_num_in_url($url , $page_param, $replacement_page){
		list($url_parsed, $url_query_array) = self::parse_url($url);
		$url_query_array[$page_param] = $replacement_page;

		$navigation_url = '';
		if( !empty($url_parsed['scheme']) )
			$navigation_url .= $url_parsed['scheme'] . '://';
		if( !empty($url_parsed['host']) )
			$navigation_url .= $url_parsed['host'];
		if( $url_parsed['path'] )
			$navigation_url .= $url_parsed['path'];
		$navigation_url .= '?';
		foreach ($url_query_array as $param => $param_value) {
			$navigation_url .= $param . '=' . $param_value . '&';
		}
		return substr($navigation_url, 0, -1);
	}

	static protected function parse_url($url){
		$url_parsed = parse_url($url);
		parse_str($url_parsed['query'], $url_query_array);
		return array($url_parsed, $url_query_array);
	}

	public function set_page_param($page_param){
		$this->page_param = $page_param;
	}

	public function set_offset_class($offset, $class){
		$this->classes[$offset] = $class;
	}

	public function set_offset_text($offset, $text){
		$this->text[$offset] = $text;
	}

	/**
	 * 设置分页风格
	 * @param integer $style 
	 */
	public function set_style($style){
		if( !in_array($style, $this->styles) )
			throw new Exception("分页风格不存在");
		$this->current_style = $style;
	}
}