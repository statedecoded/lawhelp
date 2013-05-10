<?php

/**
 * LawHelp.org API interface
 *
 * Read data from the LawHelp.org API, to gather data about legal aid programs throughout the
 * United States. Documentation of the API methods can be found at
 * <http://www.valegalaid.org/api/v2/>.
 * 
 * PHP version 5
 *
 * @author		Waldo Jaquith <waldo at jaquith.org>
 * @copyright		2013 Waldo Jaquith
 * @license		http://www.gnu.org/licenses/gpl.html GPL 3
 * @version		0.1
 *
 */

/**
 * 
 */
class LawHelp
{
	
	/**
	 * Define the URL of the API.
	 */
	public $api_url = 'http://www.valegalaid.org/api/v2/topics/';
	
	/**
	 * Index all documents
	 *
	 * Iterates through all topics at <http://www.valegalaid.org/api/v2/topics/>, stores basic data
	 * about each of them, and recurses down to the resource method and gathers all documents for that
	 * topic. Upon completion, $this->topics will be populated with the results.
	 *
	 * @param	$api_url	The URL to which the initial topics query should be sent.
	 * @return	true|false
	 */
	function harvest_documents()
	{
	
		/*
		 * Absurdly, this API prohibits requests from wget, cURL, etc. So we have to fake our user
		 * agent.
		 */
		ini_set('user_agent', 'Mozilla/5.0');
		
		$json = file_get_contents($this->api_url);
		if ($json === FALSE)
		{
			return FALSE;
		}

		$topics = json_decode($json);
		
		$tmp = new stdClass;
		foreach ($topics->entry as $topic)
		{
			
			$tmp->{$topic->id} = new stdClass;
			$tmp->{$topic->id}->title = $topic->title;
			$tmp->{$topic->id}->subtitle = $topic->subTitle;
			$tmp->{$topic->id}->api_url = urldecode($topic->content->resources->link->href);
			
		}
		
		/*
		 * Reassign the topics variable.
		 */
		$this->topics = $tmp;
		unset($tmp);
		
		/*
		 * Iterate through every topic to retrieve its member documents.
		 */
		foreach ($this->topics as &$topic)
		{
		
			$json = file_get_contents($topic->api_url);
			if ($json === FALSE)
			{
				return FALSE;
			}

			$documents = json_decode($json);
			foreach ($documents->entry as $document)
			{

				$topic->document->{$document->id}->id = $document->id;
				$topic->document->{$document->id}->title = $document->title;
				$topic->document->{$document->id}->author = $document->author->name;
				$topic->document->{$document->id}->summary = $document->summary;
				$topic->document->{$document->id}->domain = $document->content->destination;
				$topic->document->{$document->id}->url = $document->content->src;
				$topic->document->{$document->id}->mime_type = $document->content->type;
				
			}
			
		}
		
		return TRUE;
		
	} // end method index()
	
}
