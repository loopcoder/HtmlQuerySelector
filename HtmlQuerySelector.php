<?php

/**
 * HtmlQuerySelector
 * Javascript "querySelectorAll" the same function for PHP.
 * I think, some features different and missing, by Javascript.
 *
 * Info
 * This version 17.12.20.01 (year.month.day.release)
 *
 * Parameters
 * @param string $source    HTML source
 * @param string $selector  HTML selector query
 *
 * Returns
 * @return array
**/
function HtmlQuerySelector($source, $selector) {

	if (strlen($source) > 0 && strlen($selector) > 0) {

		$o = array('preg_mode' => 'usi', 'next_count' => 0, 'back_count' => 0, 'right_count' => 0, 'query_base' => '', 'query_trim' => '', 'preg_open' => '', 'preg_close' => '', 'preg_open_temp' => '', 'preg_close_temp' => '', 'preg_code_array_nth' => array(), 'isSelect' => 0, 'befSelect' => 0, 'isCount' => 0, 'isDeep' => 0, 'isSubelm' => 0, 'isUnclose' => 0, 'nth_count' => 1, 'HTML_Select' => '', 'HTML_Id' => '', 'HTML_Class' => '', 'HTML_Attribute' => '', 'element_open' => '', 'element_close' => '', 'element_uniq' => '', 'command_open' => '', 'command_close' => '', 'command_capture' => array('', ''), 'command_capture_deep' => array('', ''), 'next_array' => array(), 'back_array' => array(), 'right_array' => array(), 'preg_code_push' => array(0, 0), 'css_selectors' => '@((?P<QUOTES>\"|\').*?(?P=QUOTES))|%s@');

		$o2 = array('preg_mode' => 'si', 'preg_code_array' => array(), 'preg_open_subelm' => '', 'preg_close_subelm' => '', 'preg_group_count' => 1, 'preg_group_count_subelm' => 1, 'preg_code' => '', 'preg_code_subelm' => '', 'preg_code_array' => array(), 'preg_match' => '', 'result_match_subelm' => '');

		$o['css_selectors'] = $o['css_selectors'] . $o['preg_mode'];

		$o['next_count'] = 0;
		$o['next_array'] = preg_split('@\>@' . $o['preg_mode'], $selector, -1, PREG_SPLIT_NO_EMPTY);
		if (count($o['next_array']) > 1) {
			$o['next_count'] = count($o['next_array']) - 1;
		} else {
			$o['next_array'][$o['next_count']] = $selector;
		}
		for ($next = 0; $next <= $o['next_count']; $next = $next + 1) {
			$o['query_base'] = $o['next_array'][$next];

			$o['back_count'] = 0;
			$o['back_array'] = preg_split('@\<@' . $o['preg_mode'], $o['query_base'], -1, PREG_SPLIT_NO_EMPTY);
			if (count($o['back_array']) > 1) {
				$o['back_count'] = count($o['back_array']) - 1;
			} else {
				$o['back_array'][$o['back_count']] = $o['query_base'];
			}
			for ($back = 0; $back <= $o['back_count']; $back = $back + 1) {
				$o['query_base'] = $o['back_array'][$back];

				$o['right_count'] = 0;
				$o['right_array'] = preg_split('@\+@' . $o['preg_mode'], $o['query_base'], -1, PREG_SPLIT_NO_EMPTY);
				if (count($o['right_array']) > 1) {
					$o['right_count'] = count($o['right_array']) - 1;
				} else {
					$o['right_array'][$o['right_count']] = $o['query_base'];
				}
				for ($right = 0; $right <= $o['right_count']; $right = $right + 1) {
					$o['query_base'] = $o['right_array'][$right];

					$o['preg_open'] = '';
					$o['preg_close'] = '';
					$o['preg_code_push'] = array(0, 0);
					$o['nth_count'] = 1;

					$o['query_trim'] = preg_replace('@(\s+)@' . $o['preg_mode'], '', $o['query_base']);
					$o['query_base'] = $o['query_trim'];

					$o['query_trim'] = preg_replace(sprintf($o['css_selectors'], '\:deep\((.*?)\)'), '$1$3', $o['query_base']);
					$o['isDeep'] = ((($o['query_base'] !== $o['query_trim'])) ? 1 : 0);
					$o['query_base'] = $o['query_trim'];

					$o['query_trim'] = preg_replace(sprintf($o['css_selectors'], '\~'), '$1', $o['query_base']);
					$o['isUnclose'] = ((($o['query_base'] !== $o['query_trim'])) ? 1 : 0);
					$o['query_base'] = $o['query_trim'];

					$o['query_trim'] = preg_replace(sprintf($o['css_selectors'], '\:sub\((.*?)\)'), '$1$3', $o['query_base']);
					$o['isSubelm'] = (($o['query_base'] !== $o['query_trim']) ? 1 : 0);
					$o['query_base'] = $o['query_trim'];

					$o['query_trim'] = preg_replace(sprintf($o['css_selectors'], '\:ret\((.*?)\)'), '$1$3', $o['query_base']);
					$o['isSelect'] = ((($o['query_base'] !== $o['query_trim']) || ($o['befSelect'] == 0 && $next == $o['next_count'] && $back == $o['back_count'] && $right == $o['right_count'])) ? 1 : 0);
					if ($o['isSelect'] == 1) {
						$o['befSelect'] = 1;
					}
					$o['query_base'] = $o['query_trim'];

					$o['query_trim'] = preg_replace(sprintf($o['css_selectors'], '\:nth\-child\(.*?\)'), '$1', $o['query_base']);
					$o['isCount'] = (($o['query_base'] !== $o['query_trim']) ? 1 : 0);
					if ($o['isCount'] == 1) {
						preg_match(sprintf($o['css_selectors'], '\:nth\-child\((.*?)\)'), $o['query_base'], $queryGetCount);
						if (count($queryGetCount) == 5 && intval($queryGetCount[3]) > 1) {
							$o['nth_count'] = intval($queryGetCount[3]);
						}
					}
					$o['query_base'] = $o['query_trim'];

					preg_match('@^(.*?)\#(.*?)$@' . $o['preg_mode'], $o['query_base'], $o['HTML_Id']);
					preg_match('@^(.*?)\.(.*?)$@' . $o['preg_mode'], $o['query_base'], $o['HTML_Class']);
					preg_match('@^(.*?)\[(.*?)\].*?$@' . $o['preg_mode'], $o['query_base'], $o['HTML_Attribute']);

					if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
						$o['preg_open'] = $o['preg_open'] . '(.*?';
					}
					for ($nth = 1; $nth <= $o['nth_count']; $nth = $nth + 1) {
						$o['preg_open_temp'] = '';
						$o['preg_close_temp'] = '';
						$o['element_open'] = '';
						$o['element_close'] = '';
						$o['element_uniq'] = 'pattern_' . $next . $back . $right . $nth;
						$o['command_open'] = '';
						$o['command_close'] = '';

						if (count($o['HTML_Id']) > 2 || count($o['HTML_Class']) > 2 || count($o['HTML_Attribute']) > 2) {

							if (count($o['HTML_Id']) > 2) {
								$o['HTML_Select'] = $o['HTML_Id'];
								$o['command_open'] = '\<%s\s+?[^\>\<]*?id\=\"%s\"[^\>\<]*?\>%s';
								if ($o['isUnclose'] == 0) {
									$o['command_close'] = '\<\/%s\>%s';
								}
								$o['command_capture'] = array(
									'open' => '.*?',
									'close' => '.*?'
								);
								$o['command_capture_deep'] = array(
									'open' => '.*',
									'close' => '.*'
								);

							} else if (count($o['HTML_Class']) > 2) {
								$o['HTML_Select'] = $o['HTML_Class'];
								$o['HTML_Select'][2] = preg_replace('@[\.]@' . $o['preg_mode'], ' ', $o['HTML_Select'][2]);
								$o['command_open'] = '\<%s\s+?[^\>\<]*?class\=\"%s\"[^\>\<]*?\>%s';
								if ($o['isUnclose'] == 0) {
									$o['command_close'] = '\<\/%s\>%s';
								}
								$o['command_capture'] = array(
									'open' => '.*?',
									'close' => '.*?'
								);
								$o['command_capture_deep'] = array(
									'open' => '.*',
									'close' => '.*'
								);

							} else if (count($o['HTML_Attribute']) > 2) {
								$o['HTML_Select'] = $o['HTML_Attribute'];
								$o['command_open'] = '\<%s\s+?[^\>\<]*?%s\=\"%s\"[^\>\<]*?\>.*?';
								if ($o['isUnclose'] == 0) {
									$o['command_close'] = '\<\/%s\>%s';
								}
								$o['command_capture'] = array(
									'open' => '[^\>\<\"\']*?',
									'close' => '.*?'
								);
								$o['command_capture_deep'] = array(
									'open' => '[^\>\<\"\']*',
									'close' => '.*'
								);
							}

							if ($o['HTML_Select'][1] == '') {
								$o['element_open'] = sprintf('(?P<%s>[a-zA-Z]+?)', $o['element_uniq']);
								$o['element_close'] = sprintf('(?P=%s)', $o['element_uniq']);
								if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
									$o2['preg_group_count_subelm'] = $o2['preg_group_count_subelm'] + 1;
								} else {
									$o2['preg_group_count'] = $o2['preg_group_count'] + 1;
								}
							} else {
								$o['element_open'] = $o['HTML_Select'][1];
								$o['element_close'] = $o['HTML_Select'][1];
							}
							if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
								if ($nth == $o['nth_count']) {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], $o['element_open'], $o['HTML_Select'][2], sprintf('(%s)', $o['command_capture']['open']));
									if ($o['command_close'] !== '') {
										$o2['preg_close_subelm'] = $o2['preg_close_subelm'] . sprintf($o['command_close'], $o['element_close'], $o['command_capture']['close']);
									}
								} else {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], $o['element_open'], $o['HTML_Select'][2], $o['command_capture']['open']);
									if ($o['command_close'] !== '') {
										$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_close'], $o['element_close'], $o['command_capture']['close']);
									}
								}
							}
							$o['preg_open_temp'] = sprintf($o['command_open'], $o['element_open'], $o['HTML_Select'][2], (($o['isSelect'] == 1 && $o['isSubelm'] == 0) ? sprintf('(%s)', $o['command_capture']['open']) : $o['command_capture']['open']));
							if ($o['command_close'] !== '') {
								$o['preg_close_temp'] = sprintf($o['command_close'], $o['element_close'], (($o['isDeep'] == 1) ? $o['command_capture_deep']['close'] : $o['command_capture']['close']));
							}

						} else if (strlen($o['query_base']) > 0) {
							$o['command_open'] = '\<%s(?:\s+?[^\>\<]*?\>|\>)%s';
							if ($o['isUnclose'] == 0) {
								$o['command_close'] = '\<\/%s\>%s';
							}
							$o['command_capture'] = array(
								'open' => '.*?',
								'close' => '.*?'
							);
							$o['command_capture_deep'] = array(
								'open' => '.*',
								'close' => '.*'
							);
							if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
								if ($nth == $o['nth_count']) {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], $o['query_base'], sprintf('(%s)', $o['command_capture']['open']));
									if ($o['command_close'] !== '') {
										$o2['preg_close_subelm'] = $o2['preg_close_subelm'] . sprintf($o['command_close'], $o['query_base'], $o['command_capture']['close']);
									}
								} else {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], $o['query_base'], $o['command_capture']['open']);
									if ($o['command_close'] !== '') {
										$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_close'], $o['query_base'], $o['command_capture']['close']);
									}
								}
							}
							$o['preg_open_temp'] = sprintf($o['command_open'], $o['query_base'], (($o['isSelect'] == 1 && $o['isSubelm'] == 0) ? sprintf('(%s)', $o['command_capture']['open']) : $o['command_capture']['open']));
							if ($o['command_close'] !== '') {
								$o['preg_close_temp'] = sprintf($o['command_close'], $o['query_base'], (($o['isDeep'] == 1) ? $o['command_capture_deep']['close'] : $o['command_capture']['close']));
							}

						} else {
							$o['command_open'] = '%s';
							$o['command_close'] = '';
							$o['command_capture'] = array(
								'open' => '.*?'
							);
							$o['command_capture_deep'] = array(
								'open' => '.*'
							);
							if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
								if ($nth == $o['nth_count']) {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], sprintf('(%s)', $o['command_capture']['open']));
								} else {
									$o2['preg_open_subelm'] = $o2['preg_open_subelm'] . sprintf($o['command_open'], $o['command_capture']['open']);
								}
							}
							$o['preg_open_temp'] = sprintf($o['command_open'], sprintf((($o['isSelect'] == 1 && $o['isSubelm'] == 0) ? '(%s)' : '%s'), (($o['isDeep'] == 1) ? $o['command_capture_deep']['open'] : $o['command_capture']['open'])));
							$o['preg_close_temp'] = '';

						}

						if ($nth == $o['nth_count']) {
							$o['preg_open'] = $o['preg_open'] . $o['preg_open_temp'];
							$o['preg_close'] = $o['preg_close'] . $o['preg_close_temp'];
						} else {
							$o['preg_open'] = $o['preg_open'] . $o['preg_open_temp'];
							$o['preg_open'] = $o['preg_open'] . $o['preg_close_temp'];
						}

					}
					if ($o['isSelect'] == 1 && $o['isSubelm'] == 1) {
						$o['preg_close'] = $o['preg_close'] . ')';
					}

					if ($o['preg_open'] !== '') {
						$f_cnt = count($o['preg_code_array_nth']) - 1;

						if ($right >= 1) {
							for ($f = $f_cnt; $f >= 0; $f = $f - 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_push'] = $o['preg_code_array_nth'][$f];
									break;
								}
							}

							array_splice($o2['preg_code_array'], $o['preg_code_push'][1] + 1, 0, $o['preg_open']);
							array_splice($o2['preg_code_array'], $o['preg_code_push'][1] + 2, 0, $o['preg_close']);

							for ($f = 0; $f <= $f_cnt; $f = $f + 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_array_nth'][$f][1] = $o['preg_code_array_nth'][$f][1] + 2;
								}
							}

						} else if ($back >= 1) {
							for ($f = $f_cnt; $f >= 0; $f = $f - 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									array_splice($o['preg_code_array_nth'], $f, 1);
									break;
								}
							}
							for ($f = $f_cnt; $f >= 0; $f = $f - 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_push'] = $o['preg_code_array_nth'][$f];
									break;
								}
							}

							array_splice($o2['preg_code_array'], $o['preg_code_push'][1] + 1, 0, $o['preg_open']);
							array_splice($o2['preg_code_array'], $o['preg_code_push'][1] + 2, 0, $o['preg_close']);

							for ($f = 0; $f <= $f_cnt; $f = $f + 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_array_nth'][$f][1] = $o['preg_code_array_nth'][$f][1] + 2;
								}
							}
							if ($f_cnt <= 0) {
								array_push($o['preg_code_array_nth'], array($o['preg_code_push'][1] + 1, $o['preg_code_push'][1] + 2));
							}

						} else {
							for ($f = $f_cnt; $f >= 0; $f = $f - 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_push'] = $o['preg_code_array_nth'][$f];
									break;
								}
							}

							array_splice($o2['preg_code_array'], $o['preg_code_push'][1], 0, $o['preg_open']);
							array_splice($o2['preg_code_array'], $o['preg_code_push'][1] + 1, 0, $o['preg_close']);

							for ($f = 0; $f <= $f_cnt; $f = $f + 1) {
								if (array_key_exists($f, $o['preg_code_array_nth']) && count($o['preg_code_array_nth'][$f]) == 2) {
									$o['preg_code_array_nth'][$f][1] = $o['preg_code_array_nth'][$f][1] + 2;
								}
							}
							array_push($o['preg_code_array_nth'], array($o['preg_code_push'][1], $o['preg_code_push'][1] + 1));

						}
					}

				}

			}

		}

		unset($o);

		$o2['preg_code_array'] = implode('', $o2['preg_code_array']);
		$o2['preg_code'] = '@.*?' . $o2['preg_code_array'] . '@' . $o2['preg_mode'];
		preg_match_all($o2['preg_code'], $source, $o2['preg_match'], PREG_PATTERN_ORDER);
		if (count($o2['preg_match']) > $o2['preg_group_count']) {

			if (count($o2['preg_match'][$o2['preg_group_count']]) > 0) {
				if ($o2['preg_open_subelm'] !== '') {
					$o2['preg_code_subelm'] = '@.*?' . $o2['preg_open_subelm'] . $o2['preg_close_subelm'] . '@' . $o2['preg_mode'];
					preg_match_all($o2['preg_code_subelm'], $o2['preg_match'][$o2['preg_group_count']][0], $o2['result_match_subelm'], PREG_PATTERN_ORDER);

					if (count($o2['result_match_subelm']) > $o2['preg_group_count_subelm']) {

						return $o2['result_match_subelm'][$o2['preg_group_count_subelm']];
					}

				} else {
					return $o2['preg_match'][$o2['preg_group_count']];

				}
			}
		}

	}

	return array();
}

?>