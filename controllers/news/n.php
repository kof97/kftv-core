<?php 
	if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
	
	class N extends Ci_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('pagination');
			$this->load->helper(array('form', 'url'));

			$this->load->model('news_model');
			$this->load->model('program_model');
 		}

/**
 * PHP version 5
 * Reviewed by LYJ . 2016.1.22
 *
 * @category PHP
 * @author 	 LYJ <1048434786@qq.com>
 * @version  2015.7.13
 * @link 	 https://git.oschina.net/kofyu/KFTV-complete
 */
		public function show()
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			$data['pic6'] = $this->news_model->getPicUrl(6);
			$data['pic7'] = $this->news_model->getPicUrl(7);
			$data['pic8'] = $this->news_model->getPicUrl(8);
			$data['pic9'] = $this->news_model->getPicUrl(9);
			$data['pic10'] = $this->news_model->getPicUrl(10);
			$data['pic11'] = $this->news_model->getPicUrl(11);
			$data['pic12'] = $this->news_model->getPicUrl(12);
			$data['pic13'] = $this->news_model->getPicUrl(13);
			$data['pic14'] = $this->news_model->getPicUrl(14);
			$data['pic15'] = $this->news_model->getPicUrl(15);
			$data['pic16'] = $this->news_model->getPicUrl(16);
			$data['pic17'] = $this->news_model->getPicUrl(17);
			$data['pic26'] = $this->news_model->getPicUrl(26);

			$data['pic23'] = $this->news_model->getPicUrl(23);
			$data['pic24'] = $this->news_model->getPicUrl(24);
			$data['pic25'] = $this->news_model->getPicUrl(25);

			/*-- 视频 --*/
			$data['video1'] = $this->news_model->getVideo(1);
			$data['video2'] = $this->news_model->getVideo(2);
			$data['video3'] = $this->news_model->getVideo(3);
			$data['imgStop'] = $this->news_model->getVideo(4);
			$data['video5'] = $this->news_model->getVideo(5);
			$data['stopS'] = $this->news_model->getVideo(6);

			$data['kuaixun'] = $this->news_model->getNewsByCat(0, 20, 43);
			$data['toutiao'] = $this->news_model->getNewsByCat(0, 5, 44);

			$data['tupianxinwen_lunbo'] = $this->news_model->getNewsByCat(0, 20, 28);
			
			$data['yaowen_1'] = $this->news_model->getPicNewsByCat(0, 1, 45);
			$data['yaowen'] = $this->news_model->getNewsByCat(0, 12, 45);
			$data['shipinxinwen_1'] = $this->news_model->getVideoPicNews(0, 1, 1);
			$data['shipinxinwen'] = $this->news_model->getVideoNews(0, 12, 1);
			$data['zixunjingxuan_1'] = $this->news_model->getPicNewsByCat(0, 1, 28);
			$data['zixunjingxuan'] = $this->news_model->getZixunjingxuan();

			$data['guojixinwen'] = $this->news_model->getNewsByCat(0, 13, 1);
			$data['guoneixinwen'] = $this->news_model->getNewsByCat(0, 13, 2);
			$data['henanxinwen'] = $this->news_model->getNewsByCat(0, 13, 3);
			$data['xiaofeiweiquan'] = $this->news_model->getNewsByCat(0, 11, 10);
			$data['xingfengrexian'] = $this->news_model->getNewsByCat(0, 11, 11);

			$data['relapinglun_1'] = $this->news_model->getPicNewsByCat(0, 1, 6);
			$data['relapinglun'] = $this->news_model->getNewsByCat(0, 12, 6);
			$data['waimeikankaifeng_1'] = $this->news_model->getPicNewsByCat(0, 1, 5);
			$data['waimeikankaifeng'] = $this->news_model->getNewsByCat(0, 12, 5);

			$data['benwangbobao_1'] = $this->news_model->getPicNewsByCat(0, 1, 22);
			$data['benwangbobao'] = $this->news_model->getNewsByCat(0, 11, 22);
			$data['zhitongkaifeng_1'] = $this->news_model->getPicNewsByCat(0, 1, 8);
			$data['zhitongkaifeng'] = $this->news_model->getNewsByCat(0, 11, 8);
			$data['bianliangshiping_1'] = $this->news_model->getPicNewsByCat(0, 1, 7);
			$data['bianliangshiping'] = $this->news_model->getNewsByCat(0, 11, 7);

			$data['fangchanxinwen_1'] = $this->news_model->getPicNewsByCat(0, 1, 13);
			$data['fangchanxinwen'] = $this->news_model->getNewsByCat(0, 9, 13);
			$data['qichexinwen_1'] = $this->news_model->getPicNewsByCat(0, 1, 12);
			$data['qichexinwen'] = $this->news_model->getNewsByCat(0, 9, 12);
			$data['nongyexinwen_1'] = $this->news_model->getPicNewsByCat(0, 1, 14);
			$data['nongyexinwen'] = $this->news_model->getNewsByCat(0, 9, 14);
			$data['jiankangxinwen_1'] = $this->news_model->getPicNewsByCat(0, 1, 15);
			$data['jiankangxinwen'] = $this->news_model->getNewsByCat(0, 9, 15);
			$data['yangshengxinwen_1'] = $this->news_model->getPicNewsByCat(0, 1, 16);
			$data['yangshengxinwen'] = $this->news_model->getNewsByCat(0, 9, 16);

			$data['wenhuaxinwen'] = $this->news_model->getNewsByCat(0, 13, 18);
			$data['lvyouxinwen'] = $this->news_model->getNewsByCat(0, 13, 17);
			$data['jiaoyuxinwen'] = $this->news_model->getNewsByCat(0, 13, 19);
			$data['caijingxinwen'] = $this->news_model->getNewsByCat(0, 13, 31);

			$data['meishixinwen'] = $this->news_model->getNewsByCat(0, 13, 30);
			$data['teyuetongxun'] = $this->news_model->getNewsByCat(0, 13, 21);
			$data['weishixianxinwen'] = $this->news_model->getNewsByCat(0, 13, 24);
			$data['qixianxinwen'] = $this->news_model->getNewsByCat(0, 13, 25);
			$data['tongxuxianxinwen'] = $this->news_model->getNewsByCat(0, 13, 26);
			$data['lankaoxianxinwen'] = $this->news_model->getNewsByCat(0, 13, 27);
			$data['tupianxinwen'] = $this->news_model->getNewsByCat(0, 13, 28);
			$data['wangshangminsheng'] = $this->news_model->getNewsByCat(0, 13, 29);

			$this->load->view('news/index', $data);
		}

		public function newsList() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			/*-- list / news --*/
			$data['pic18'] = $this->news_model->getPicUrl(18);
			$data['pic19'] = $this->news_model->getPicUrl(19);
			$data['pic20'] = $this->news_model->getPicUrl(20);
			$data['pic21'] = $this->news_model->getPicUrl(21);
			$data['pic22'] = $this->news_model->getPicUrl(22);

			$catId = intval($this->uri->segment(4));
			/*-- page --*/
			$pageSize = 20;

			$this->db->from('news_article');
			$this->db->where('category_id', $catId);
			$total = $this->db->count_all_results();
			$pageUri = 'news/n/newsList/' . $catId;
			$offset = intval($this->uri->segment(5));

			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri, 5);
			$data['cat'] = $this->news_model->getCatMessage($catId);
			$data['artList'] = $this->news_model->getNewsByCat($offset, $pageSize, $catId);

			$data['redianxinwen'] = $this->news_model->getHitsNews(0, 6);
			$data['shishixinwen'] = $this->news_model->getRecentlyNews(0, 7);

			$this->load->view('news/newslist', $data);
		}

		public function newsListV() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			/*-- list / news --*/
			$data['pic18'] = $this->news_model->getPicUrl(18);
			$data['pic19'] = $this->news_model->getPicUrl(19);
			$data['pic20'] = $this->news_model->getPicUrl(20);
			$data['pic21'] = $this->news_model->getPicUrl(21);
			$data['pic22'] = $this->news_model->getPicUrl(22);

			/*-- page --*/
			$pageSize = 20;

			$this->db->from('news_article');
			$this->db->where('has_video', 1);
			$total = $this->db->count_all_results();
			$pageUri = 'news/n/newsListV/';
			$offset = intval($this->uri->segment(4));

			$data['page'] = $this->news_model->page($pageSize, $offset, $total, $pageUri);
			$data['cat'] = "视频新闻";
			$data['artList'] = $this->news_model->getVideoNews($offset, $pageSize, 1);

			$data['redianxinwen'] = $this->news_model->getHitsNews(0, 6);
			$data['shishixinwen'] = $this->news_model->getRecentlyNews(0, 7);

			$this->load->view('news/newslist', $data);
		}

		public function showNews() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			/*-- list / news --*/
			$data['pic18'] = $this->news_model->getPicUrl(18);
			$data['pic19'] = $this->news_model->getPicUrl(19);
			$data['pic20'] = $this->news_model->getPicUrl(20);
			$data['pic21'] = $this->news_model->getPicUrl(21);
			$data['pic22'] = $this->news_model->getPicUrl(22);

			$artId = intval($this->uri->segment(5));
			$catId = intval($this->uri->segment(4));

			$data['cat'] = $this->news_model->getCatMessage($catId);
			$data['art'] = $this->news_model->getNewsDetail($artId);

			$data['comment_total'] = $this->news_model->getCommentsCount($artId);
			$rest = $data['comment_total'] - 3;
			if ($rest < 0) {
				$rest = 0;
			}
			$data['comment_block'] = $this->news_model->getNewsComments(0, 3, $artId);
			$data['comment_none'] = $this->news_model->getNewsComments(3, $rest, $artId);
			
			$data['redianxinwen'] = $this->news_model->getHitsNews(0, 6);
			$data['shishixinwen'] = $this->news_model->getRecentlyNews(0, 7);

			$this->news_model->addHits($artId);
			$this->load->view('news/news', $data);
		}

		public function showNewsP() 
		{
			/*-- head --*/
			$data['pic1'] = $this->news_model->getPicUrl(1);
			$data['pic2'] = $this->news_model->getPicUrl(2);
			$data['pic3'] = $this->news_model->getPicUrl(3);
			$data['pic4'] = $this->news_model->getPicUrl(4);
			$data['pic5'] = $this->news_model->getPicUrl(5);
			$data['dianshilanmu'] = $this->program_model->getAllP(0, 22);

			/*-- list / news --*/
			$data['pic22'] = $this->news_model->getPicUrl(22);

			$artId = intval($this->uri->segment(5));
			$catId = intval($this->uri->segment(4));

			$data['cat'] = $this->news_model->getCatMessage($catId);
			$data['art'] = $this->news_model->getNewsDetail($artId);

			$data['comment_total'] = $this->news_model->getCommentsCount($artId);
			$rest = $data['comment_total'] - 3;
			if ($rest < 0) {
				$rest = 0;
			}
			$data['comment_block'] = $this->news_model->getNewsComments(0, 3, $artId);
			$data['comment_none'] = $this->news_model->getNewsComments(3, $rest, $artId);
			
			$this->news_model->addHits($artId);
			$this->load->view('news/pic_news', $data);
		}

/**
 * @author 	 LYJ <1048434786@qq.com>
 *
 * Time: 2015.7.23
 * Function: front end comment
 * Reviewed by LYJ . 2016.1.22
 */
		public function pubComment0() 
		{
  			$commentContent = $this->input->post('comment_content');
  			$time = date('Y-m-d h:i:s');
  			$catId = intval($this->uri->segment(4));
  			$artId = intval($this->uri->segment(5));
  			$ischecked_c = 1;

  			$pubUser = "游客";

  			$this->news_model->addComment($commentContent, $time, $ischecked_c, $artId, $pubUser);	
  			if ($catId == 28) {
  				redirect('news/n/showNewsP/' . $catId . "/" . $artId);
  			} else {
  				redirect('news/n/showNews/' . $catId . "/" . $artId);
  			}
		}

		public function pubComment1() 
		{
  			$commentContent = $this->input->post('comment_content');
  			$time = date('Y-m-d h:i:s');
  			$catId = intval($this->uri->segment(4));
  			$artId = intval($this->uri->segment(5));
  			$ischecked_c = 0;

  			$pubUser = "游客";

  			$this->news_model->addComment($commentContent, $time, $ischecked_c, $artId, $pubUser);	
  			if ($catId == 28) {
  				redirect('news/n/showNewsP/' . $catId . "/" . $artId);
  			} else {
  				redirect('news/n/showNews/' . $catId . "/" . $artId);
  			}
		}
		
	}