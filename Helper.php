<?php
/**
 * @link http://pagination.ru/
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 */
class Krugozor_Pagination_Helper
{
    /**
     * ����������� ��� ���������:
     * ���  ��  �  1 2 3 4 5 6 7 8 9 10  �  ��  ���
     *
     * @var int
     */
    const PAGINATION_NORMAL_TYPE = 1;

    /**
     * ��� ������������ ������������ ���������:
     * ���  ��  �  50-41 40-31 30-21 20-11 10-1  �  ��  ���
     *
     * @var int
     */
    const PAGINATION_DECREMENT_TYPE = 2;

    /**
     * ��� ������������ ������������ ���������:
     * ���  ��  �  1-10 11-20 21-30 31-40 41-50  �  ��  ���
     *
     * @var int
     */
    const PAGINATION_INCREMENT_TYPE = 3;

    /**
     * @var Krugozor_Pagination_Manager
     */
    private $manager;

    /**
     * ��������� CSS-������� ��� ������� <a> �������� ����������.
     *
     * @var array
     */
    private $styles = array();

    /**
     * ��������� ��� ����=>�������� ��� ����������� � QUERY_STRING
     * ����������� ����������.
     *
     * @var array
     */
    private $request_uri_params = array();

    /**
     * ����� � title ��� ���� ��������� <a> ����������.
     *
     * @var array
     */
    private $html = array
    (
        'first_page_anchor'  => '���',
        'previous_block_anchor'  => '��',
        'previous_page_anchor'   => '�',
        'next_page_anchor'  => '�',
        'next_block_anchor' => '��',
        'last_page_anchor'   => '���',

        'first_page_title' => '�� ������ ��������',
        'previous_block_title' => '���������� ��������',
        'previous_page_title'  => '���������� ��������',
        'next_page_title' => '��������� ��������',
        'next_block_title' => '��������� ��������',
        'last_page_title'  => '�� ��������� ��������',
    );

    /**
     * ���������� �� ������� <a> '���'.
     *
     * @var bool
     */
    private $view_first_page_label = true;

    /**
     * ���������� �� ������� <a> '���'.
     *
     * @var bool
     */
    private $view_last_page_label = true;

    /**
     * ���������� �� ������� <a> '��'.
     *
     * @var bool
     */
    private $view_previous_block_label = true;

    /**
     * ���������� �� ������� <a> '��'.
     *
     * @var bool
     */
    private $view_next_block_label = true;

    /**
     * ������������� ��������� (#primer), ����������� �� ��������� ����� ������������ ���������.
     *
     * @var string
     */
    private $fragment_identifier;

    /**
     * ��� ���������� ���������� (��. ��������� ������ PAGINATION_*_TYPE).
     *
     * @var int
     */
    private $pagination_type;

    /**
     * @param Krugozor_Pagination_Manager $manager
     */
    public function __construct(Krugozor_Pagination_Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * ���������� ������ Krugozor_Pagination_Manager
     *
     * @param void
     * @return Krugozor_Pagination_Manager
     */
    public function getPagination()
    {
        return $this->manager;
    }

    /**
     * ������������� ��� ���������� ����������.
     *
     * @param int
     * @return Krugozor_Pagination_Helper
     */
    public function setPaginationType($pagination_type)
    {
        $this->pagination_type = (int) $pagination_type;

        return $this;
    }

    /**
     * ������������� ��������� �������� ��� QUERY_STRING
     * ����������� ����������.
     *
     * @param string $key
     * @param string $value
     * @return Krugozor_Pagination_Helper
     */
    public function setRequestUriParameter($key, $value)
    {
        $this->request_uri_params[$key] = (string) $value;

        return $this;
    }

    /**
     * �������������, ���������� �� ������� <a> '���'.
     *
     * @param bool $value
     * @return Krugozor_Pagination_Helper
     */
    public function setViewFirstPageLabel($value)
    {
        $this->view_first_page_label = (bool) $value;

        return $this;
    }

    /**
     * �������������, ���������� �� ������� <a> '���'.
     *
     * @param bool $value
     * @return Krugozor_Pagination_Helper
     */
    public function setViewLastPageLabel($value)
    {
        $this->view_last_page_label = (bool) $value;

        return $this;
    }

    /**
     * �������������, ���������� �� ������� <a> '��'.
     *
     * @param bool $value
     * @return Krugozor_Pagination_Helper
     */
    public function setViewPreviousBlockLabel($value)
    {
        $this->view_previous_block_label = (bool) $value;

        return $this;
    }

    /**
     * �������������, ���������� �� ������� <a> '��'.
     *
     * @param bool $value
     * @return Krugozor_Pagination_Helper
     */
    public function setViewNextBlockLabel($value)
    {
        $this->view_next_block_label = (bool) $value;

        return $this;
    }

    /**
     * ������������� ������������� ��������� (#primer) ����������� ����������.
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setFragmentIdentifier($fragment_identifier)
    {
        $this->fragment_identifier = trim((string) $fragment_identifier, ' #');

        return $this;
    }

    /**
     * ������������� CSS-����� ������� �������� <a> � ���������� ����������.
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssNormalLinkClass($class)
    {
        $this->styles['normal_link_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <span> � ���������� ����������,
     * �������� �������� ������� � ������� ������.
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssActiveLinkClass($class)
    {
        $this->styles['active_link_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '���'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssFirstPageClass($class)
    {
        $this->styles['first_page_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '���'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssLastPageClass($class)
    {
        $this->styles['last_page_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '��'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssPreviousBlockClass($class)
    {
        $this->styles['previous_block_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '��'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssNextBlockClass($class)
    {
        $this->styles['next_block_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '�'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssPreviousPageClass($class)
    {
        $this->styles['previous_page_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� CSS-����� �������� <a> '�'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setCssNextPageClass($class)
    {
        $this->styles['next_page_class'] = (string) $class;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '���'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setFirstPageAnchor($anchor)
    {
        $this->html['first_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '���'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setLastPageAnchor($anchor)
    {
        $this->html['last_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '��'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setPreviousBlockAnchor($anchor)
    {
        $this->html['previous_block_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '��'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setNextBlockAnchor($anchor)
    {
        $this->html['next_block_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '�'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setPreviousPageAnchor($anchor)
    {
        $this->html['previous_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ������������� ����� ��� �������� <a> '�'
     *
     * @param string
     * @return Krugozor_Pagination_Helper
     */
    public function setNextPageAnchor($anchor)
    {
        $this->html['next_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * ��������� � ���������� HTML-��� ������ ���������.
     *
     * @param void
     * @return string
     */
    public function getHtml()
    {
        ob_start();

        $self_uri = $this->createRequestUri();
        $qs = $this->createQueryString();
    ?>
    <? if ($this->view_first_page_label && $this->manager->getCurrentSeparator() && $this->manager->getCurrentSeparator() != 1): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('first_page_class', 'normal_link_class')?> title="<?=$this->html['first_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getFirstPage()?>&amp;<?=$this->manager->getSeparatorName()?>=1<?=$this->createFragmentIdentifier()?>"><?=$this->html['first_page_anchor']?></a>&nbsp;
    <? endif; ?>

    <? if ($this->view_previous_block_label && $this->manager->getPreviousBlockSeparator()): ?>
        <a<?=$this->createInlineCssClassDeclaration('previous_block_class', 'normal_link_class')?> title="<?=$this->html['previous_block_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getPageForPreviousBlock()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getPreviousBlockSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['previous_block_anchor']?></a>&nbsp;
    <? endif; ?>

    <? if($this->manager->getPreviousPageSeparator() && $this->manager->getPreviousPage()): ?>
        <a<?=$this->createInlineCssClassDeclaration('previous_page_class', 'normal_link_class')?> title="<?=$this->html['previous_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getPreviousPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getPreviousPageSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['previous_page_anchor']?></a>&nbsp;
    <? endif; ?>

    <? foreach($this->manager->getTemplateData() as $row): ?>
        <? if($this->manager->getCurrentPage() == $row["page"]): ?>
            <span<?=$this->createInlineCssClassDeclaration('active_link_class')?>><?=$this->createHyperlinkAnchor($row)?></span>
        <? else: ?>
            <a<?=$this->createInlineCssClassDeclaration('normal_link_class')?> href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getSeparatorName()?>=<?=$row["separator"]?>&amp;<?=$this->manager->getPageName()?>=<?=$row["page"]?><?=$this->createFragmentIdentifier()?>"><?=$this->createHyperlinkAnchor($row)?></a>
        <? endif; ?>
    <? endforeach; ?>

    <? if($this->manager->getNextPageSeparator() && $this->manager->getNextPage()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('next_page_class', 'normal_link_class')?> title="<?=$this->html['next_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getNextPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getNextPageSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['next_page_anchor']?></a>
    <? endif; ?>

    <? if($this->view_next_block_label && $this->manager->getNextBlockSeparator()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('next_block_class', 'normal_link_class')?> title="<?=$this->html['next_block_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getSeparatorName()?>=<?=$this->manager->getNextBlockSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['next_block_anchor']?></a>
    <? endif; ?>

    <? if ($this->view_last_page_label && $this->manager->getLastSeparator() && $this->manager->getCurrentSeparator() != $this->manager->getLastSeparator()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('last_page_class', 'normal_link_class')?> title="<?=$this->html['last_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getLastPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getLastSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['last_page_anchor']?></a>
    <? endif; ?>
<?php
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

    /**
     * ������ ����� ��� �������� <a> � ����������� �� ���� $this->pagination_type.
     *
     * @param array $params
     * @return string
     */
    private function createHyperlinkAnchor(array $params)
    {
        switch ($this->pagination_type)
        {
            case self::PAGINATION_DECREMENT_TYPE:
                return $params['decrement_anhor'];

            case self::PAGINATION_INCREMENT_TYPE:
                return $params['increment_anhor'];

            case self::PAGINATION_NORMAL_TYPE:
            default:
                return $params['page'];
        }
    }

    /**
     * ���������� ������ ���� `class="class_name"` ���� $class_name �������� �
     * ������ � $this->styles[$class_name].
     * � �������� ������ ���������� ������ ���� `class="replacement_class_name"`,
     * ���� $replacement_class_name �������� � �������� ��������� ������ �
     * ������ � $this->styles[$replacement_class_name].
     * ���� $replacement_class_name �� ��������, ������������ ������ ������.
     *
     * @param string ��� CSS-������
     * @param string ��� CSS-������
     * @return string
     */
    private function createInlineCssClassDeclaration($class_name, $replacement_class_name=null)
    {
        return !empty($this->styles[$class_name])
               ? ' class="' . $this->styles[$class_name] . '"'
               : ($replacement_class_name === null
                  ? ''
                  : call_user_func_array(array($this, __METHOD__), array($replacement_class_name))
                 );
    }

    /**
     * ���������� ������������� ��������� � �������� #
     * ��� ����������� ��������������� � URL-�����.
     *
     * @param void
     * @return string
     */
    private function createFragmentIdentifier()
    {
        return !empty($this->fragment_identifier) ? '#' . $this->fragment_identifier : '';
    }

    /**
     * ���������� REQUEST_URI ��� QUERY_STRING.
     *
     * @param void
     * @return string
     */
    private function createRequestUri()
    {
        if (strpos($_SERVER["REQUEST_URI"], '?') !== false)
        {
            return substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], '?'));
        }
        else
        {
            return $_SERVER["REQUEST_URI"];
        }
    }

    /**
     * ������� ������ QUERY_STRING �� ������� ���������� $this->request_uri_params.
     *
     * @param void
     * @return string
     */
    private function createQueryString()
    {
        $query_string = '';

        foreach ($this->request_uri_params as $key => $value)
        {
            if ((string) $value !== '')
            {
                $query_string .= $key . '=' . htmlentities(urlencode($value)) . '&amp;';
            }
        }

        return $query_string;
    }
}