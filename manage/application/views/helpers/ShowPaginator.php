<?
class Zend_View_Helper_showPaginator
{
	public function showPaginator($view)
	{
	 	?>
			��<? echo $view->paginator->getTotalItemCount(); ?>��&nbsp;
			<? echo $view->paginator->count(); ?>ҳ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<? echo $view->paginationControl($view->paginator, 'Sliding', 'paginator.phtml'); ?>
	 	<?
	}
}
