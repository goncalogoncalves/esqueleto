<?php

namespace Esqueleto\Model;

use Esqueleto\Classes\StringsNumbers;

/**
 * Model Page
 */
class PageModel extends BaseModel
{

    /**
     * Responsible to get a page from the db
     * @return array
     */
    public function getPageById($id)
    {
        $id = (int) $id;
        $this->db->query('SELECT * FROM view_page WHERE page_id = :id_page AND private = 0 AND visible = 1');
        $this->db->bind(':id_page', $id);

        $row = $this->db->single();

        return $row;
    }

    /**
     * Responsible to get a page from the db
     * @return array
     */
    public function getPageByType($type)
    {

        $type = mb_strtolower($type);

        $this->db->query('SELECT * FROM view_page WHERE LOWER(type_name) = :type_name AND private = 0 AND visible = 1 AND lang_id = :lang_id');
        $this->db->bind(':type_name', $type);
        $this->db->bind(':lang_id', $_SESSION['LANG_ID']);
        $row = $this->db->single();

        return $row;
    }

    /**
     * Responsible for the setup of the general seo.
     */
    public function setupGeneralSEO($arrayInfo)
    {
        $_SESSION['WEBSITE_HTML_TITLE'] = $_SESSION['WEBSITE_HTML_PRE_TITLE'] . $arrayInfo['seo_title'] . $_SESSION['WEBSITE_HTML_POS_TITLE'];
        $_SESSION['WEBSITE_DESCRIPTION'] = $_SESSION['WEBSITE_PRE_DESCRIPTION'] . $arrayInfo['seo_description'] . $_SESSION['WEBSITE_POS_DESCRIPTION'];
        $_SESSION['WEBSITE_KEYWORDS'] = $_SESSION['WEBSITE_PRE_KEYWORDS'] . ',' . $arrayInfo['seo_keywords'] . ',' . $_SESSION['WEBSITE_POS_KEYWORDS'];
    }

    public function preparePage($page)
    {
        $page = $this->prepareContentForChildPages($page);

        return $page;
    }

    public function prepareContentForChildPages($page)
    {
        $stringChildPagePos = null;
        $pageContent = stripcslashes(stripcslashes($page['content']));
        $numberChildPages = substr_count($pageContent, '[pagina]');

        while ($numberChildPages > 0) {

            $stringsNumbersObj = new stringsNumbers();
            $arrayString = $stringsNumbersObj->getStringBetween($pageContent, '[pagina]', '[/pagina]');
            $stringChildPageNr = $arrayString[0];
            $stringChildPagePos = $arrayString[1];
            $stringChildPageSize = $arrayString[2];

            if ($stringChildPagePos != '' && $stringChildPagePos != null && $stringChildPagePos > 0) {
                $childPage = $this->getPageById($stringChildPageNr);
                $htmlChildPage = $childPage["content"];
                $stringChildPage = '[pagina]' . $stringChildPageNr . '[/pagina]';
                $pageContent = str_replace($stringChildPage, $htmlChildPage, $pageContent);
            }
            --$numberChildPages;
        }

        $page['content'] = $pageContent;

        return $page;
    }

}
