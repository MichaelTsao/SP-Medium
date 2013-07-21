<?
class Zend_View_Helper_showPaginator
{
	public function showPaginator($view)
	{
	 	?>
			¹²<? echo $view->paginator->getTotalItemCount(); ?>Ìõ&nbsp;
			<? echo $view->paginator->count(); ?>Ò³ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<? echo $view->paginationControl($view->paginator, 'Sliding', 'paginator.phtml'); ?>
	 	<?
	}
}
