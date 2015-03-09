<?php

class View{
	public $base_path;
	public $template;
	public $template_file;
	public $vars;

	public static $share_vars;

	public function __construct($path){
		$this->base_path = $path;
	}

	public function make($template){
		$this->template = $template;
		$this->template_file = $this->base_path . '/' . $template;
		if (!file_exists($this->template_file)){
			throw new Exception("file {$this->template_file} does not exist");
		}
		return $this;
	}

	public function with($vars){
		if (!isset($this->vars))
			$this->vars = array();
		$this->vars = array_merge($this->vars, $vars);
		return $this;
	}

	/**
	 * share with : 'all' : share with all
	 *              f['+' => []] : share only
	 *              ['-' => []] : share all - these
	 */
	public function shareWith($vars, $with='all'){
		$id = $this->generateId($vars);

		if (!isset(self::$share_vars[$id])){
			self::$share_vars[$id]['all'] = [];
			self::$share_vars[$id]['+'] = [];
			self::$share_vars[$id]['-'] = [];
		}
		self::$share_vars[$id]['value'] = $vars;

		if ($with == 'all'){
			self::$share_vars[$id]['all'] = true;
		}
		if (isset($with['+'])){
			self::$share_vars[$id]['+'] = array_merge(self::$share_vars[$id]['+'], $with['+']);
		}
		if (isset($with['-'])){
			self::$share_vars[$id]['-'] = array_merge(self::$share_vars[$id]['-'], $with['-']);
		}
	}

	/**
	 * 视图文件中可以直接使用 $this->property/method
	 */
	public function __toString(){
		if (!isset($this->template))
			throw new Exception("there is no template");

		if (isset(self::$share_vars) && is_array(self::$share_vars)){
			foreach (self::$share_vars as $id => $share_value) {
				if (isset($share_value['-']) && in_array($this->template, $share_value['-']))
					continue;
				if (isset($share_value['all']) && $share_value['all'])
					extract($share_value['value']);
				if (isset($share_value['+']) && in_array($this->template, $share_value['+']))
					extract($share_value['value']);
			}
		}

		// 放在后面保证和share重名时，覆盖 share 中的变量
		if (isset($this->vars))
			extract($this->vars);
		include $this->template_file;
		return '';
	}

	public function get($var, $default=null){
		if (isset($this->vars[$var]))
			return $this->vars[$var];

		if (!isset($this->template))
			throw new Exception("there is no template");

		if (isset(self::$share_vars) && is_array(self::$share_vars)){
			foreach (self::$share_vars as $id => $share_value) {
				if (!isset($share_value['value'][$var]))
					continue;
				if (in_array($this->template, $share_value['-'])){
					break;
				}
				return $share_value['value'][$var];
			}
		}
		return $default;
	}

	public function test(){
		echo "View->test()";
	}

	public function generateId($vars){
		return md5(serialize($vars));
	}

}
