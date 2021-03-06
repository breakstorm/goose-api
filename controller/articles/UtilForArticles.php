<?php
namespace Controller\articles;
use Exception, Core, Controller;

/**
 * util for articles
 */

class UtilForArticles {

  /**
   * get next page number
   *
   * @param Core\Model $model
   * @param string $where
   * @return int
   * @throws
   */
  public static function getNextPage($model, $where)
  {
    try
    {
      if (!$_GET['page']) $_GET['page'] = 1;
      $_GET['page'] = (int)$_GET['page'] + 1;
      $_GET['field'] = 'srl';

      $next_output = Controller\Main::index((object)[
        'model' => $model,
        'table' => 'articles',
        'field' => 'srl',
        'where' => $where,
      ]);
      if ($next_output->data && $next_output->data->index && count($next_output->data->index))
      {
        return (int)$_GET['page'];
      }
      return null;
    }
    catch(Exception $e)
    {
      return null;
    }
  }

  /**
   * extend category name in items
   *
   * @param Core\Model $model
   * @param array $index
   * @return array
   */
  public static function extendCategoryNameInItems($model, $index)
  {
    if (!(isset($index) && count($index))) return [];

    foreach ($index as $k=>$v)
    {
      if (!$v->category_srl) continue;
      $category = $model->getItem((object)[
        'table' => 'categories',
        'field' => 'name',
        'where' => 'srl='.(int)$v->category_srl,
      ]);
      if ($category->data && $category->data->name)
      {
        $index[$k]->category_name = $category->data->name;
      }
    }

    return $index;
  }

  /**
   * extend nest name in items
   *
   * @param Core\Model $model
   * @param array $index
   * @return array
   */
  public static function extendNestNameInItems($model, $index)
  {
    if (!(isset($index) && count($index))) return [];

    foreach ($index as $k=>$v)
    {
      if (!$v->nest_srl) continue;
      $nest = $model->getItem((object)[
        'table' => 'nests',
        'field' => 'name',
        'where' => 'srl='.(int)$v->nest_srl,
      ]);
      if ($nest->data && $nest->data->name)
      {
        $index[$k]->nest_name = $nest->data->name;
      }
    }

    return $index;
  }

  /**
   * type 값 구분용 where 쿼리 만들기
   *
   * @return string
   */
  public static function getWhereType()
  {
    // 모든 글 가져오기
    if ($_GET['visible_type'] === 'all')
    {
      return ' and NOT type LIKE \'ready\'';
    }
    // 특정 type 글 가져오기
    else if ($_GET['visible_type'])
    {
      switch ($_GET['visible_type'])
      {
        case 'ready':
        case 'private':
          return ' and type LIKE \''.$_GET['visible_type'].'\'';
        default:
          return ' and type LIKE \'public\'';
      }
    }
    // 공개된 글만 가져오기
    else
    {
      return ' and type LIKE \'public\'';
    }
  }

}
