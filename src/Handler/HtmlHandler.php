<?php

namespace Micorx\Welper\Handler;

class HtmlHandler
{

	private $document_root_;

	private $remote_doamin_;

	function __construct()
	{
		$document_ = $_SERVER['DOCUMENT_ROOT'];
		$remote_ = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
		if (!defined('DOCUMENT_ROOT_CUSTOM')) {
			define('DOCUMENT_ROOT_CUSTOM', $document_);
		} else {
			if (!is_string(DOCUMENT_ROOT_CUSTOM) || DOCUMENT_ROOT_CUSTOM == '') {
				define('DOCUMENT_ROOT_CUSTOM', $document_);
			}
		}
		$this->document_root_ = DOCUMENT_ROOT_CUSTOM;

		if (!defined('REMOTE_DOMAIN_CUSTOM')) {
			define('REMOTE_DOMAIN_CUSTOM', $remote_);
		} else {
			if (!is_string(REMOTE_DOMAIN_CUSTOM) || REMOTE_DOMAIN_CUSTOM == '') {
				define('REMOTE_DOMAIN_CUSTOM', $remote_);
			}
		}
		$this->remote_doamin_ = REMOTE_DOMAIN_CUSTOM;
	}

	private function s_get_url_remote($url)
	{
		if (is_string($url) && $url !== '' && strpos($url, ' ') === false) {
			return $url;
		}
		return false;
	}

	private function s_get_url_local($path)
	{
		if (is_string($path) && $path !== '' && strpos($path, ' ') === false) {
			if (substr($path, 0, 1) == '/') {
				return $this->remote_doamin_ . $path;
			}
		}
		return false;
	}

	private function s_get_path_local($path)
	{
		if ($this->s_check_file($path)) {
			return $this->document_root_ . $path;
		}
		return false;
	}

	private function s_check_file($path)
	{
		if (is_file($this->document_root_ . $path)) {
			return true;
		}
		return false;
	}

	/* **************************************
	 **************** HEAD ******************
	 **************************************** */
	function e_head_validate_tag($tags, $values)
	{
		if (is_array($tags) && count($tags) > 0) {
			$head = $tags;
		} else {
			$head = array();
		}
		if (is_array($values) && count($values) > 0) {
			$head = array();
			foreach ($values as $key => $value) {
				$key_multiple = false;
				switch ($key) {
					case 'all-title':
						$key_multiple = array(
							'title',
							'm-title',
							'm-og-title',
							'm-tw-title'
						);
						$v = $this->head_validate_string($value);
						break;
					case 'all-description':
						$key_multiple = array(
							'm-description',
							'm-og-description',
							'm-tw-description'
						);
						$v = $this->head_validate_string($value);
						break;
					case 'all-image':
						$key_multiple = array(
							'm-og-image',
							'm-tw-image'
						);
						$v = $this->head_validate_image($value);
						break;
					case 'm-telephone-no':
					case 'm-x-ua-compatible':
						$v = $this->head_validate_boolean($value);
						break;
					case 'title':
					case 'm-title':
					case 'm-charset':
					case 'm-viewport':
					case 'm-robots':
					case 'm-description':
					case 'm-author':
					case 'm-og-title':
					case 'm-og-description':
					case 'm-og-type':
					case 'm-og-site':
					case 'm-tw-title':
					case 'm-tw-description':
					case 'm-tw-card':
					case 'm-tw-site':
					case 'm-fb-appid':
						$v = $this->head_validate_string($value);
						break;
					case 'm-og-locale':
						$v = $this->head_validate_locale($value);
						break;
					case 'm-og-locale-alt':
						$v = $this->head_validate_locale_alt($value);
						break;
					case 'm-canonical':
					case 'm-og-url':
						$v = $this->head_validate_url($value);
						break;
					case 'm-og-image':
					case 'm-tw-image':
						$v = $this->head_validate_image($value);
						break;
					case 'l-alternate':
						$v = $this->head_validate_alternate($value);
						break;
					case 'l-favicon':
						$v = $this->head_validate_links($value);
						break;
					case 'l-css':
						$v = $this->head_validate_links($value);
						break;
					case 's-js':
						$v = $this->head_validate_script($value);
						break;
					default:
						$v = false;
						break;
				}
				if ($v !== false) {
					if ($key_multiple !== false && is_array($key_multiple)) {
						foreach ($key_multiple as $key_val) {
							$head[$key_val] = $v;
						}
					} elseif (is_string($key) || is_numeric($key)) {
						$head[$key] = $v;
					}
				}
			}
			if (is_array($head) && count($head) > 0) {
				return $head;
			}
		}
		return false;
	}

	private function head_validate_boolean($value)
	{
		if ($value === true) {
			return true;
		}
		return false;
	}

	private function head_validate_string($value)
	{
		if (is_string($value) && $value !== '') {
			return str_replace('"', "'", trim($value));
		}
		return false;
	}

	private function head_validate_locale($value)
	{
		if (is_string($value)) {
			return $value;
		}
		return false;
	}

	private function head_validate_locale_alt($value)
	{
		if (is_array($value)) {
			$list = array();
			foreach ($value as $element) {
				array_push($list, $element);
			}
			if (count($list) > 0) {
				return $list;
			}
		}
		return false;
	}

	private function head_validate_url($value)
	{
		if (is_array($value) && isset($value['uri'])) {
			$uri = trim($value['uri']);
			$uri_type = 'abs';
			if (isset($value['uri-type']) && is_string($value['uri-type'])) {
				$uri_type = $value['uri-type'];
			}
			switch ($uri_type) {
				case 'rel':
					return $this->s_get_url_local($uri);
					break;
				case 'abs':
				default:
					return $this->s_get_url_remote($uri);
					break;
			}
		}
		return false;
	}

	private function head_validate_links($value)
	{
		if (is_array($value)) {
			$resources = array();
			foreach ($value as $element) {
				$resource = $this->head_validate_link($element);
				if ($resource !== false) {
					array_push($resources, $resource);
				}
			}
			if (count($resources) > 0) {
				return $resources;
			}
		}
		return false;
	}

	private function head_validate_alternate($value)
	{
		if (is_array($value)) {
			$resources = array();
			$default_setted = false;
			$resource_d = false;
			foreach ($value as $element) {
				$resource = $this->head_validate_link($element, false);
				if ($resource !== false) {
					array_push($resources, $resource);
				}
				if (!$default_setted && isset($element['default']) && $element['default'] === true) {
					$resource_d = $resource;
					$resource_d['hreflang'] = 'x-default';
					$default_setted = true;
				}
			}
			if ($resource_d !== false) {
				array_push($resources, $resource_d);
			}
			if (count($resources) > 0) {
				return $resources;
			}
		}
		return false;
	}

	private function head_validate_link($value, $is_file = true)
	{
		$resource = array();
		$uri_valid = false;
		$uri = false;
		$uri_type = 'abs';
		if (is_string($value) && $value !== '') {
			$uri = $value;
		} elseif (is_array($value) && isset($value['uri'])) {
			$uri = trim($value['uri']);
			if (isset($value['uri-type'])) {
				$uri_type = $value['uri-type'];
			}
		}
		if ($uri !== false && $uri !== '' && $uri_type !== false && $uri_type !== '') {
			switch ($uri_type) {
				case 'rel':
					if ($is_file) {
						if ($this->s_check_file($uri)) {
							$resource['uri'] = $this->s_get_url_local($uri);
							$uri_valid = true;
						}
					} else {
						$resource['uri'] = $this->s_get_url_local($uri);
						$uri_valid = true;
					}
					break;
				case 'abs':
				default:
					$resource['uri'] = $this->s_get_url_remote($uri);
					$uri_valid = true;
					break;
			}
		}
		if ($uri_valid === false) {
			return false;
		}
		if (isset($value['crossorigin'])) {
			$resource['crossorigin'] = $value['crossorigin'];
		}
		if (isset($value['integrity'])) {
			$resource['integrity'] = $value['integrity'];
		}
		if (isset($value['referrerpolicy'])) {
			$resource['referrerpolicy'] = $value['referrerpolicy'];
		}
		if (isset($value['disabled']) && $value['disabled'] === true) {
			$resource['disabled'] = $value['disabled'];
		}
		if (isset($value['hreflang'])) {
			$resource['hreflang'] = $value['hreflang'];
		}
		if (isset($value['imagesizes'])) {
			$resource['imagesizes'] = $value['imagesizes'];
		}
		if (isset($value['imagesrcset'])) {
			$resource['imagesrcset'] = $value['imagesrcset'];
		}
		if (isset($value['media'])) {
			$resource['media'] = $value['media'];
		}
		if (isset($value['rel'])) {
			$resource['rel'] = $value['rel'];
		}
		if (isset($value['sizes'])) {
			$resource['sizes'] = $value['sizes'];
		}
		if (isset($value['title'])) {
			$resource['title'] = $value['title'];
		}
		if (isset($value['type'])) {
			$resource['type'] = $value['type'];
		}
		if (count($resource) > 0) {
			return $resource;
		}
		return false;
	}

	private function head_validate_image($value)
	{
		if (is_array($value)) {
			if (isset($value['uri'])) {
				$uri = trim($value['uri']);
				$path_remote = false;
				$path_local = false;
				if (isset($value['uri-type'])) {
					switch ($value['uri-type']) {
						case 'rel':
							if ($this->s_check_file($uri)) {
								$path_remote = $this->s_get_url_local($uri);
								$path_local = $this->s_get_path_local($uri);
							}
							break;
						case 'abs':
						default:
							$path_remote = $this->s_get_url_remote($uri);
							break;
					}
				}
				if ($path_remote !== false) {
					$image = array(
						'uri' => $path_remote
					);
					if (isset($value['alt'])) {
						$alt = $this->head_validate_string($value['alt']);
						if ($alt !== false) {
							$image['alt'] = $alt;
						}
					}
					if ($path_local !== false) {
						list($w, $h) = getimagesize($path_local);
						if (isset($w) && isset($h) && is_numeric($w) && is_numeric($h)) {
							$image['width'] = $w;
							$image['height'] = $h;
						} else {
							return false;
						}
					}
					return $image;
				}
			}
		}
		return false;
	}

	private function head_validate_script($value)
	{
		if (is_array($value)) {
			$scripts = array();
			foreach ($value as $element) {
				$resource = $this->head_validate_link($element);
				if ($resource !== false) {
					$script = array(
						'uri' => $resource['uri']
					);
					if (isset($element['type']) && is_string($element['type'])) {
						$script['type'] = $element['type'];
					}
					if (isset($element['defer']) && $element['defer'] === true) {
						$script['defer'] = $element['defer'];
					}
					if (isset($element['async']) && $element['async'] === true) {
						$script['async'] = $element['async'];
					}
					if (isset($element['integrity']) && is_string($element['integrity'])) {
						$script['integrity'] = $element['integrity'];
					}
					if (isset($element['crossorigin']) && is_string($element['crossorigin'])) {
						$script['crossorigin'] = $element['crossorigin'];
					}
					if (isset($element['referrerpolicy']) && is_string($element['referrerpolicy'])) {
						$script['referrerpolicy'] = $element['referrerpolicy'];
					}
					array_push($scripts, $script);
				}
			}
			if (count($scripts) > 0) {
				return $scripts;
			}
		}
		return false;
	}

	function e_head_compose($values, $order)
	{
		if (is_array($values) && count($values) > 0) {
			if ($order === false) {
				$order = array_keys($values);
			}
			if (is_array($order) && count($order) > 0) {
				$html = '';
				foreach ($order as $order_el) {
					if (isset($values[$order_el])) {
						$key = $order_el;
						$value = $values[$order_el];
						switch ($order_el) {
							case 'm-telephone-no':
							case 'm-x-ua-compatible':
								$html .= $this->head_compose_boolean($key, $value);
								break;
							case 'title':
							case 'm-title':
							case 'm-charset':
							case 'm-viewport':
							case 'm-robots':
							case 'm-description':
							case 'm-author':
							case 'm-og-title':
							case 'm-og-description':
							case 'm-og-type':
							case 'm-og-site':
							case 'm-tw-title':
							case 'm-tw-description':
							case 'm-tw-card':
							case 'm-tw-site':
							case 'm-fb-appid':
								$html .= $this->head_compose_string($key, $value);
								break;
							case 'm-og-locale':
							case 'm-og-locale-alt':
								$html .= $this->head_compose_locale($value);
								break;
							case 'm-canonical':
							case 'm-og-url':
								$html .= $this->head_compose_uri($key, $value);
								break;
							case 'm-og-image':
							case 'm-tw-image':
								$html .= $this->head_compose_image($key, $value);
								break;
							case 'l-alternate':
								$html .= $this->head_compose_alternate($value);
								break;
							case 'l-favicon':
								$html .= $this->head_compose_favicon($value);
								break;
							case 'l-css':
								$html .= $this->head_compose_css($value);
								break;
							case 's-js':
								$html .= $this->head_compose_js($value);
								break;
						}
					}
				}
				if ($html !== '') {
					return $html;
				}
			}
		}
		return false;
	}

	private function head_compose_boolean($key, $value)
	{
		$html = '';
		if (is_string($key) && $key !== '' && $value === true) {
			switch ($key) {
				case 'm-telephone-no':
					$html .= '<meta name="format-detection" content="telephone=no">';
					break;
				case 'm-x-ua-compatible':
					$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
					break;
			}
		}
		if ($html !== '') {
			$html = $html . PHP_EOL;
		}
		return $html;
	}

	private function head_compose_string($key, $value)
	{
		$html = '';
		if (is_string($key) && $key !== '' && is_string($value) && $value !== '') {
			switch ($key) {
				case 'title':
					$html .= '<title>' . $value . '</title>';
					break;
				case 'm-title':
					$html .= '<meta name="title" content="' . $value . '" />';
					break;
				case 'm-charset':
					$html .= '<meta charset="' . $value . '" />';
					break;
				case 'm-viewport':
					$html .= '<meta name="viewport" content="' . $value . '" />';
					break;
				case 'm-robots':
					$html .= '<meta name="robots" content="' . $value . '" />';
					break;
				case 'm-description':
					$html .= '<meta name="description" content="' . $value . '" />';
					break;
				case 'm-author':
					$html .= '<meta name="author" content="' . $value . '" />';
					break;
				case 'm-og-title':
					$html .= '<meta property="og:title" content="' . $value . '" />';
					break;
				case 'm-og-description':
					$html .= '<meta property="og:description" content="' . $value . '" />';
					break;
				case 'm-og-type':
					$html .= '<meta property="og:type" content="' . $value . '" />';
					break;
				case 'm-og-site':
					$html .= '<meta property="og:site_name" content="' . $value . '" />';
					break;
				case 'm-tw-title':
					$html .= '<meta property="twitter:title" content="' . $value . '" />';
					break;
				case 'm-tw-description':
					$html .= '<meta property="twitter:description" content="' . $value . '" />';
					break;
				case 'm-tw-card':
					$html .= '<meta property="twitter:card" content="' . $value . '" />';
					break;
				case 'm-tw-site':
					$html .= '<meta property="twitter:site" content="@' . $value . '" />';
					break;
				case 'm-fb-appid':
					$html .= '<meta property="fb:app_id" content="' . $value . '" />';
					break;
			}
		}
		if ($html !== '') {
			$html = $html . PHP_EOL;
		}
		return $html;
	}

	private function head_compose_locale($value)
	{
		$html = '';
		if (is_array($value)) {
			foreach ($value as $element) {
				if (is_string($element)) {
					$html .= '<meta property="og:locale:alternate" content="' . $element . '" />';
					$html .= PHP_EOL;
				}
			}
		} elseif (is_string($value)) {
			$html .= '<meta property="og:locale" content="' . $value . '" />';
			$html .= PHP_EOL;
		}
		return $html;
	}

	private function head_compose_uri($key, $value)
	{
		$html = '';
		if (is_string($key) && $key !== '') {
			switch ($key) {
				case 'm-canonical':
					$html .= '<link rel="canonical" href="' . $value . '" />';
					break;
				case 'm-og-url':
					$html .= '<meta property="og:url" content="' . $value . '" />';
					break;
			}
		}
		if ($html !== '') {
			$html = $html . PHP_EOL;
		}
		return $html;
	}

	private function head_compose_alternate($value)
	{
		$html = '';
		if (is_array($value)) {
			foreach ($value as $element) {
				if (isset($element['hreflang']) && $element['hreflang'] !== '' && isset($element['uri']) && $element['uri'] !== '') {
					$html .= '<link rel="alternate" hreflang="' . $element['hreflang'] . '" href="' . $element['uri'] . '" />';
					$html .= PHP_EOL;
				}
			}
		}
		return $html;
	}

	private function head_compose_image($key, $value)
	{
		$html = '';
		if (is_string($key) && $key !== '') {
			switch ($key) {
				case 'm-og-image':
					if (isset($value['uri']) && $value['uri'] !== '') {
						$html .= '<meta property="og:image" content="' . $value['uri'] . '" />';
						if (isset($value['width']) && $value['width'] !== '') {
							$html .= PHP_EOL;
							$html .= '<meta property="og:image:width" content="' . $value['width'] . '" />';
						}
						if (isset($value['height']) && $value['height'] !== '') {
							$html .= PHP_EOL;
							$html .= '<meta property="og:image:height" content="' . $value['height'] . '" />';
						}
						if (isset($value['alt']) && $value['alt'] !== '') {
							$html .= PHP_EOL;
							$html .= '<meta property="og:image:alt" content="' . $value['alt'] . '" />';
						}
					}
					break;
				case 'm-tw-image':
					if (isset($value['uri']) && $value['uri'] !== '') {
						$html .= '<meta property="twitter:image" content="' . $value['uri'] . '" />';
						if (isset($value['alt']) && $value['alt'] !== '') {
							$html .= PHP_EOL;
							$html .= '<meta property="twitter:image:alt" content="' . $value['alt'] . '" />';
						}
					}
					break;
			}
		}
		if ($html !== '') {
			$html = $html . PHP_EOL;
		}
		return $html;
	}

	private function head_compose_favicon($value)
	{
		$html = '';
		if (is_array($value)) {
			foreach ($value as $element) {
				if (isset($element['uri']) && $element['uri'] !== '') {
					$data = '';
					if (isset($element['rel']) && $element['rel'] !== '') {
						$data .= ' rel="' . $element['rel'] . '"';
					}
					if (isset($element['type']) && $element['type'] !== '') {
						$data .= ' type="' . $element['type'] . '"';
					}
					if (isset($element['sizes']) && $element['sizes'] !== '') {
						$data .= ' sizes="' . $element['sizes'] . '"';
					}
					$html .= '<link' . $data . ' href="' . $element['uri'] . '"' . ' />' . PHP_EOL;
				}
			}
		}
		return $html;
	}

	private function head_compose_css($value)
	{
		$html = '';
		if (is_array($value)) {
			foreach ($value as $element) {
				if (isset($element['uri']) && $element['uri'] !== '') {
					$html .= '<link rel="stylesheet" type="text/css" href="' . $element['uri'] . '" />' . PHP_EOL;
				}
			}
		}
		return $html;
	}

	private function head_compose_js($value)
	{
		$html = '';
		if (is_array($value)) {
			foreach ($value as $element) {
				if (isset($element['uri']) && $element['uri'] !== '') {
					$data = '';
					if (isset($element['type']) && is_string($element['type'])) {
						$data .= ' type="' . $element['type'] . '"';
					}
					if (isset($element['defer']) && $element['defer'] === true) {
						$data .= ' defer';
					}
					if (isset($element['async']) && $element['async'] === true) {
						$data .= ' async';
					}
					if (isset($element['integrity']) && is_string($element['integrity'])) {
						$data .= ' integrity="' . $element['integrity'] . '"';
					}
					if (isset($element['crossorigin']) && is_string($element['crossorigin'])) {
						$data .= ' crossorigin="' . $element['crossorigin'] . '"';
					}
					if (isset($element['referrerpolicy']) && is_string($element['referrerpolicy'])) {
						$data .= ' referrerpolicy="' . $element['referrerpolicy'] . '"';
					}
					if (isset($element['uri']) && is_string($element['uri'])) {
						$html .= '<script src="' . $element['uri'] . '"' . $data . '></script>' . PHP_EOL;
					}
				}
			}
		}
		return $html;
	}

	/* *****************************************
	 ***************** PICTURE *****************
	 ******************************************* */

	function e_picture_validate_queries($data)
	{
		$error = false;
		$format = array(
			'jpg',
			'png',
			'svg',
			'webp'
		);
		if ($data === true) {
			return array(
				array(
					'media' => '',
					'suffix' => '',
					'separator' => '',
					'format' => '',
					'default' => true
				)
			);
		}

		$media_queries_t = array();
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $query) {
				if (is_array($query) && count($query) > 0 && $error !== true) {
					$query_t = array(
						'separator' => '-',
						'format' => '.jpg',
						'default' => false
					);
					foreach ($query as $key => $value) {
						if (!isset($query_t[$key])) {
							if ($key == 'media') {
								if (is_string($value)) {
									$query_t[$key] = trim($value);
								} else {
									$error = $error || true;
									break;
								}
							} elseif ($key == 'suffix') {
								if (is_string($value)) {
									$query_t[$key] = trim($value);
								} else {
									$error = $error || true;
									break;
								}
							} else {
								$error = $error || true;
								break;
							}
						} else {
							if ($key == 'separator') {
								if (is_string($value)) {
									$query_t[$key] = trim($value);
								} else {
									$error = $error || true;
									break;
								}
							} elseif ($key == 'format') {
								if (is_string($value) && in_array(trim($value), $format)) {
									$query_t[$key] = '.' . trim($value);
								} else {
									$error = $error || true;
									break;
								}
							} elseif ($key == 'default') {
								if ($value === true || $value === false) {
									$query_t[$key] = $value;
								} else {
									$error = $error || true;
									break;
								}
							} else {
								$error = $error || true;
								break;
							}
						}
					}
					array_push($media_queries_t, $query_t);
				} else {
					$error = $error || true;
					break;
				}
			}
			return $media_queries_t;
		}
		return false;
	}

	function e_picture_validate_lazy($data)
	{
		if ($data === true || $data === false) {
			return $data;
		}
		return -2;
	}

	function e_picture_validate_id($data)
	{
		if ($data !== null) {
			if (is_string($data) && strpos(trim($data), ' ') == false) {
				return trim($data);
			}
		}
		return false;
	}

	function e_picture_validate_classes($data)
	{
		if ($data !== null) {
			if (is_string($data)) {
				return trim($data);
			}
		}
		return false;
	}

	function e_picture_validate_properties($data)
	{
		if ($data !== null) {
			if (is_string($data)) {
				return trim($data);
			}
		}
		return false;
	}

	function e_picture_compose($picture_options, $image_path, $image_alt, $image_external)
	{
		if (!is_string($image_path)) {
			return false;
		} else {
			$path = $image_path;
		}
		if (!is_string($image_alt)) {
			return false;
		} else {
			$alt = $image_alt;
		}
		$txt_id = '';
		$txt_classes = '';
		$txt_classes_t = '';
		$txt_props = '';
		$txt_alt = '';
		$txt_med = '';
		$txt_nod = '';
		$txt_def = '';
		$txt_nosc = '';
		// ID
		if (isset($picture_options['id']) && $picture_options['id'] !== '') {
			$txt_id .= ' id="' . $picture_options['id'] . '"';
		}
		// CLASSES
		if (isset($picture_options['classes']) && $picture_options['classes'] !== '') {
			$txt_classes_t .= $picture_options['classes'];
		}
		if (isset($picture_options['lazy']) && $picture_options['lazy'] === true) {
			if ($txt_classes_t !== '') {
				$txt_classes_t .= ' ';
			}
			$txt_classes_t .= 'image-lazy-loading';
		}
		if ($txt_classes_t !== '') {
			$txt_classes .= ' class="';
			$txt_classes .= $txt_classes_t;
			$txt_classes .= '"';
		}
		// PROPERTIES
		if (isset($picture_options['props']) && $picture_options['props'] !== '') {
			$txt_props .= ' ' . $picture_options['props'];
		}
		// ALT
		if ($alt !== '') {
			$txt_alt .= ' alt="' . $alt . '"';
		}
		$queries_def = false;
		if (!isset($picture_options['media_queries']) || !is_array($picture_options['media_queries'])) {
			return false;
		}
		foreach ($picture_options['media_queries'] as $query) {
			$path_remote = false;
			if ($image_external === true) {
				$path_remote = $this->s_get_url_remote($path);
			} else {
				$uri = $path;
				if (isset($query['separator']) && isset($query['suffix']) && $query['suffix'] !== '') {
					$uri .= $query['separator'] . $query['suffix'];
				}
				if (isset($query['format'])) {
					$uri .= $query['format'];
				}
				if ($this->s_check_file($uri)) {
					$path_remote = $this->s_get_url_local($uri);
				}
			}
			if ($path_remote !== false) {
				if (isset($picture_options['lazy']) && $picture_options['lazy'] === true) {
					$text_data = 'data-';
				} else {
					$text_data = '';
				}
				if ($queries_def == false || (isset($query['default']) && $query['default'] === true)) {
					$txt_def .= '<img ' . $text_data . 'src="' . $path_remote . '"' . $txt_alt . '>' . PHP_EOL;
					if (isset($picture_options['lazy']) && $picture_options['lazy'] === true) {
						$txt_nosc = '<noscript>';
						$txt_nosc .= '<img ' . $text_data . 'src="' . $path_remote . '"' . $txt_alt . '>';
						$txt_nosc .= '</noscript>' . PHP_EOL;
					}
					$queries_def = true;
				}
				if (isset($query['media']) && $query['media'] !== '') {
					$txt_med .= '<source media="(' . $query['media'] . ')" ';
					$txt_med .= $text_data . 'srcset="' . $path_remote . '"';
					$txt_med .= '>' . PHP_EOL;
				} else {
					$txt_nod .= '<source ';
					$txt_nod .= $text_data . 'srcset="' . $path_remote . '"';
					$txt_nod .= '>' . PHP_EOL;
				}
			}
		}
		if ($txt_def === '') {
			return false;
		}
		$txt = '';
		$txt .= '<picture' . $txt_id . $txt_classes . $txt_props . '>' . PHP_EOL;
		$txt .= $txt_med;
		$txt .= $txt_nod;
		$txt .= $txt_def;
		$txt .= '</picture>';
		$txt .= $txt_nosc;
		return $txt;
	}
}
