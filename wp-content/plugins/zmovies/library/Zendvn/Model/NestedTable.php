<?php
namespace Zendvn\Model;

if(!class_exists('wpdb')){
    require_once ABSPATH . 'wp-includes/wp-db.php';
}
class NestedTable extends \wpdb {
	
    protected $_table;
    protected $_alias;
    protected $_sql;
	
	public function __construct($table, $alias) {
	    parent::__construct( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
	    $this->_table = $table;
	    $this->_alias = $alias;
	}
	
	public function insertNode($data, $parent,$format, $options){
	    // @todo: working
		$parentInfo   = $this->getNodeInfo(array('id' => $parent));

		$dataLeft             = array('left' => '`left` + 2');
		$formatLeft           = array('%s');
		$whereLeftFormat      = array('%d');
		
		$dataRight            = array('right' => '`right` + 2');
		$formatRight          = array('%s');
		$whereRightFormat     = array('%d');
		
		switch ($options['position']) {
			case 'left':
				
				$whereLeft          = array('left' => $parentInfo->left);
				$whereRight         = array('right' => $parentInfo->left);
				$operatorLeft       = array('left' => '>');
				$operatorRight      = array('right' => '>');
				
				$data['parent']	= $parentInfo->id;
				$data['level']	= $parentInfo->level + 1;
				$data['left']	= $parentInfo->left + 1;
				$data['right']	= $parentInfo->left + 2;
				
				break;
			case 'before':
				
				$whereLeft          = array('left' => $parentInfo->left);
				$whereRight         = array('right' => $parentInfo->left);
				$operatorLeft       = array('left' => '>=');
				$operatorRight      = array('right' => '>');
				
				$data['parent']	= $parentInfo->parent;
				$data['level']	= $parentInfo->level;
				$data['left']	= $parentInfo->left;
				$data['right']	= $parentInfo->left + 1;
				break;
			case 'after':
				
				$whereLeft          = array('left' => $parentInfo->right);
				$whereRight         = array('right' => $parentInfo->right);
				$operatorLeft       = array('left' => '>=');
				$operatorRight      = array('right' => '>');
				
				$data['parent']	= $parentInfo->parent;
				$data['level']	= $parentInfo->level;
				$data['left']	= $parentInfo->right + 1;
				$data['right']	= $parentInfo->right + 2;
				break;
			case 'right':
			default:
				
				$whereLeft          = array('left' => $parentInfo->right);
				$whereRight         = array('right' => $parentInfo->right);
				
				$operatorLeft       = array('left' => '>');
				$operatorRight      = array('right' => '>=');
				
				$data['parent']	= $parentInfo->id;
				$data['level']	= $parentInfo->level + 1;
				$data['left']	= $parentInfo->right;
				$data['right']	= $parentInfo->right + 1;
				break;
		}
		
		$this->updateNotEqual($dataLeft, $whereLeft,$formatLeft,$whereLeftFormat,$operatorLeft);
		$this->updateNotEqual($dataRight, $whereRight,$formatRight,$whereRightFormat,$operatorRight);
		
		// $format - parent - level - left - right
		$format = array_merge($format, array('%d','%d','%d','%d'));
		$this->insert($this->_table, $data,$format);
		
		return $this->insert_id;
	}
	private function updateNotEqual( $data, $where, $format = null, $where_format = null, $operators ){
	    if ( ! is_array( $data ) || ! is_array( $where ) ) {
			return false;
		}

		$data = $this->process_fields( $this->_table, $data, $format );
		if ( false === $data ) {
			return false;
		}
		$where = $this->process_fields( $this->_table, $where, $where_format );
		if ( false === $where ) {
			return false;
		}

		$fields = $conditions = $values = array();
		foreach ( $data as $field => $value ) {
			$fields[] = "`$field` = " . $value['format'];
			$values[] = $value['value'];
		}
		foreach ( $where as $field => $value ) {
			$operator = isset($operators[$field]) ? $operators[$field] : '=';
			$conditions[] = "`$field` " . $operator . " " . $value['format'];
			$values[] = $value['value'];
		}

		$fields = implode( ', ', $fields );
		$conditions = implode( ' AND ', $conditions );

		$sql = "UPDATE `$this->_table` SET $fields WHERE $conditions";

		$this->check_current_query = false;
		$sql = $this->prepare( $sql, $values );
		$sql = str_replace("'", '', $sql);
		
		return $this->query( $sql );
	}
	private function deleteNotEqual($where, $where_format = null,$operators = null ) {
	    if ( ! is_array( $where ) ) {
	        return false;
	    }
	
	    $where = $this->process_fields( $this->_table, $where, $where_format );
	    if ( false === $where ) {
	        return false;
	    }
	
	    $conditions = $values = array();
	    foreach ( $where as $field => $value ) {
	        $operator = isset($operators[$field]) ? $operators[$field] : '=';
			$conditions[] = "`$field` " . $operator . " " . $value['format'];
	        $values[] = $value['value'];
	    }
	
	    $conditions = implode( ' AND ', $conditions );
	
	    $sql = "DELETE FROM `$this->_table` WHERE $conditions";
	
	    $this->check_current_query = false;
	    
	    $sql = $this->prepare( $sql, $values );
	    $sql = str_replace("'", '', $sql);
	    
	    return $this->query( $sql );
	}
	public function listNodes($arrParam = null, $options = null){
		
// 		if($options == null) {
// 			$result	= $this->tableGateway->select(function (Select $select) use ($arrParam){
// 				$select->columns(array('id','name','level', 'parent'))
// 					   ->order('left ASC')
// 					   ->where->greaterThan('level', 0);
// 				;	
// 			});
			
// 		}
		
// 		if($options['task'] == 'list-level') {
// 			echo '<h3 style="color:red;">' . __METHOD__ . '</h3>';
// 			$result	= $this->tableGateway->select(function (Select $select) use ($arrParam){
// 				$select->columns(array('id','name','level', 'parent'))
// 					   ->order('left ASC')
// 					   ->where->greaterThan('level', 0)
// 					   ->where->lessThanOrEqualTo('level', $arrParam['level'])
// 				;
// 			});
// 		}
		
// 		if($options['task'] == 'list-branch') {
// 			$nodeInfo	= $this->getNodeInfo($arrParam);
// 			$result	= $this->tableGateway->select(function (Select $select) use ($nodeInfo){
// 				$select->columns(array('id','name','level', 'parent'))
// 					   ->order('left ASC')
// 					   ->where->greaterThan('level', 0)
// 					   ->where->between('left',$nodeInfo->left,$nodeInfo->right)				   
// 				;
// 			});
			
// 		}
		
// 		if($options['task'] == 'list-breadcrumd') {
// 			$nodeInfo	= $this->getNodeInfo($arrParam);
// 			$result	= $this->tableGateway->select(function (Select $select) use ($nodeInfo){
// 				$select->columns(array('id','name','level', 'parent'))
// 					   ->order('left ASC')
// 					   ->where->greaterThan('level', 0)
// 					   ->where->lessThanOrEqualTo('left', $nodeInfo->left)
// 					   ->where->greaterThanOrEqualTo('right', $nodeInfo->right)
// 				;
// 			});
					
// 		}
		
		if($options['task'] == 'list-childs') {
			/* $result	= $this->tableGateway->select(function (Select $select) use ($arrParam){
				$select->columns(array('id','name','level'))
					   ->order('left ASC')
						->where->equalTo('parent', $arrParam->id)
				;
			}); */
		    $this->_sql = 'SELECT ' . $this->_alias . '.id, '
		                              . $this->_alias . '.name, '
		                              . $this->_alias . '.level'
                          . ' FROM ' . $this->_table . ' AS ' . $this->_alias
                          . ' WHERE ' . $this->_alias . '.parent = ' . (int)$arrParam->id
		                  . ' ORDER BY ' . $this->_alias . '.left ASC'
		                  ;
		    $result  = $this->get_results($this->_sql,OBJECT);
					
		}
		
		if($options['task'] == 'move-up') {
			$nodeInfo	= $this->getNodeInfo($arrParam);
			
			/* $result	= $this->tableGateway->select(function (Select $select) use ($nodeInfo){
				$select->columns(array('id','name','level','left', 'right', 'parent'))
					   ->order('left DESC')
					   ->limit(1)
					   ->where->lessThan('right', $nodeInfo->left)
					   ->where->notEqualTo('id', $nodeInfo->id)
					   ->where->equalTo('parent', $nodeInfo->parent);
				;
			})->current(); */
			
			$this->_sql = 'SELECT ' . $this->_alias . '.id, '
                    			    . $this->_alias . '.name, '
                			        . $this->_alias . '.level, '
                			        . $this->_alias . '.left, '
                			        . $this->_alias . '.right, '
                			        . $this->_alias . '.parent'
			            . ' FROM ' . $this->_table . ' AS ' . $this->_alias
			            . ' WHERE ' . $this->_alias . '.right < ' . $nodeInfo->left
			            . ' AND ' . $this->_alias . '.id != ' . $nodeInfo->id
			            . ' AND ' . $this->_alias . '.parent = ' . $nodeInfo->parent
			            . ' ORDER BY ' . $this->_alias . '.left DESC'
			            . ' LIMIT 1'
			                ;
            $result  = $this->get_row($this->_sql,OBJECT);
		}
		
		if($options['task'] == 'move-down') {
			$nodeInfo	= $this->getNodeInfo($arrParam);
				
			/* $result	= $this->tableGateway->select(function (Select $select) use ($nodeInfo){
				$select->columns(array('id','name','level','left', 'right', 'parent'))
						->order('left ASC')
						->limit(1)
						->where->greaterThan('left', $nodeInfo->right)
						->where->notEqualTo('id', $nodeInfo->id)
						->where->equalTo('parent', $nodeInfo->parent);
				;
			})->current(); */
			
			$this->_sql = 'SELECT ' . $this->_alias . '.id, '
                    			    . $this->_alias . '.name, '
                			        . $this->_alias . '.level, '
			                        . $this->_alias . '.left, '
			                        . $this->_alias . '.right, '
			                        . $this->_alias . '.parent'
                        . ' FROM ' . $this->_table . ' AS ' . $this->_alias
                        . ' WHERE ' . $this->_alias . '.left > ' . $nodeInfo->right
                        . ' AND ' . $this->_alias . '.id != ' . $nodeInfo->id
                        . ' AND ' . $this->_alias . '.parent = ' . $nodeInfo->parent
                        . ' ORDER BY ' . $this->_alias . '.left ASC'
                        . ' LIMIT 1'
                            ;
            $result  = $this->get_row($this->_sql,OBJECT);
		}
		
		return $result;
	}
	
	public function getNodeInfo($arrParam = null, $options = null){
	
		if($options == null) {
			$this->_sql = 'SELECT ' . $this->_alias . '.id, '
			                        . $this->_alias . '.name, '
			                        . $this->_alias . '.level, '
			                        . $this->_alias . '.left, '
			                        . $this->_alias . '.right, '
			                        . $this->_alias . '.parent'
    			        . ' FROM ' . $this->_table . ' AS ' . $this->_alias
    			        . ' WHERE ' . $this->_alias . '.id=' . (int)$arrParam['id']
                         ;
			
            $result  = $this->get_row($this->_sql,OBJECT);
		}
		
		return $result;
	}
	
	public function detachBranch($nodeMoveID, $options = null){//1
		$moveInfo	= $this->getNodeInfo(array('id' => $nodeMoveID));
		$moveLeft	= $moveInfo->left;
		$moveRight	= $moveInfo->right;
		$totalNode	= ($moveRight - $moveLeft + 1)/2;
		
		// ================================== Node on branch ==================================
		if($options == null){
			/* $data 	= array(
					'left' 	=> new Expression('`left` - ?', array($moveLeft)),
					'right' => new Expression('`right` - ?', array($moveRight))
			);
			$where	= new Where();
			$where->between('left', $moveInfo->left, $moveInfo->right);
			$this->tableGateway->update($data, $where); */
    		
    		$data             = array('left' => '`left` - ' . (int)($moveLeft), 'right' => '`right` - ' . (int)$moveRight );
    		$format           = array('%s','%s');
    		
    		$where            = array('left' => $moveLeft . ' AND ' . $moveRight);
    		$whereFormat      = array('%s');
    		$operators        = array('left' => 'BETWEEN');
    		
    		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		}
		
		if($options['task'] == 'remove-node'){
			/* $where	= new Where();
			$where->between('left', $moveInfo->left, $moveInfo->right);
			$this->tableGateway->delete($where); */
			
			/* $sql     = 'DELETE `' . $this->_table . '`' .
            			' WHERE `left` BETWEEN ' . $moveLeft . ' AND ' . $moveRight;
			
			$this->check_current_query = true;
			$this->query( $sql ); */
			
			$where 			= array('left' => $moveLeft . ' AND ' . $moveRight);
			$where_format 	= array('%s');
			$operators        = array('left' => 'BETWEEN');
			$this->deleteNotEqual($where,$whereFormat,$operators);
			//$this->_wpdb->delete($this->_table, $where,$where_format);
		}
		
		// ================================== Node on tree (LEFT) ==================================
		/* $data = array(
				'left' 	=> new Expression('`left` - ?', array($totalNode * 2)),
		);
		$where = new Where();
		$where->greaterThan('left', $moveRight);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` - ' . (int)($totalNode * 2 ) );
		$format           = array('%s');
		
		$where            = array('left' => $moveRight);
		$whereFormat      = array('%d');
		$operators        = array('left' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ================================== Node on tree (RIGHT) ==================================
		/* $data = array(
				'right' 	=> new Expression('`right` - ?', array($totalNode * 2)),
		);
		$where = new Where();
		$where->greaterThan('right', $moveInfo->right);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` - ' . (int)($totalNode * 2 ) );
		$format           = array('%s');
		
		$where            = array('right' => $moveRight);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		return $totalNode;
	}

	public function moveNode($nodeMoveID, $nodeSelectionID, $options){
		switch ($options['position']) {
			case 'left':
				$this->moveLeft($nodeMoveID, $nodeSelectionID);
				break;
			case 'before':
				$this->moveBefore($nodeMoveID, $nodeSelectionID);
				break;
			case 'after':
				$this->moveAfter($nodeMoveID, $nodeSelectionID);
				break;
			case 'right':
			default:
				$this->moveRight($nodeMoveID, $nodeSelectionID);
			break;
		}
		
	}
	
	public function moveRight($nodeMoveID, $nodeSelectionID){
		// ========================= Detach branch =========================
		$totalNode	= $this->detachBranch($nodeMoveID);
		
		$nodeSelectionInfo	= $this->getNodeInfo(array('id' => $nodeSelectionID));
		$nodeMoveInfo		= $this->getNodeInfo(array('id' => $nodeMoveID));
		
		// ========================= Node on tree (LEFT) ========================= 
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('left', $nodeSelectionInfo->right);
		$where->greaterThan('right', 0);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('left' => $nodeSelectionInfo->right, 'right' => 0);
		$whereFormat      = array('%d','%d');
		$operators        = array('left' => '>','right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on tree (RIGHT) =========================
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThanOrEqualTo('right', $nodeSelectionInfo->right);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('right' => $nodeSelectionInfo->right);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEVEL) =========================
		/* $where	= new Where();
		$where->lessThanOrEqualTo('right', 0);
		
		$data 	= array(
				'level' 	=> new Expression('`level` + ?', array($nodeSelectionInfo->level - $nodeMoveInfo->level + 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('level' => '`level` + ' . (int)($nodeSelectionInfo->level - $nodeMoveInfo->level + 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEFT) =========================		
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($nodeSelectionInfo->right))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . $nodeSelectionInfo->right);
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (RIGHT) =========================
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($nodeSelectionInfo->right + $totalNode*2 - 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($nodeSelectionInfo->right + $totalNode*2 - 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node move (PARENT) =========================
		/* $data 	= array(
				'parent' 	=> $nodeSelectionInfo->id
		);
		$this->tableGateway->update($data, array('id' => $nodeMoveInfo->id)); */
		$this->updateNodeMove($nodeMoveInfo->id, $nodeSelectionInfo->id);
	}
	
	public function moveLeft($nodeMoveID, $nodeSelectionID){
		
		// ========================= Detach branch =========================
		$totalNode	= $this->detachBranch($nodeMoveID);
		
		$nodeSelectionInfo	= $this->getNodeInfo(array('id' => $nodeSelectionID));
		$nodeMoveInfo		= $this->getNodeInfo(array('id' => $nodeMoveID));
		
		// ========================= Node on tree (LEFT) ========================= data + where -
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('left', $nodeSelectionInfo->left);
		$where->greaterThan('right', 0);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('left' => $nodeSelectionInfo->left, 'right' => 0);
		$whereFormat      = array('%d', '%d');
		$operators        = array('left' => '>','right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on tree (RIGHT) ========================= data + where -
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('right', $nodeSelectionInfo->left);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('right' => $nodeSelectionInfo->left);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEVEL) ========================= data + where +
		/* $where	= new Where();
		$where->lessThanOrEqualTo('right', 0);
		
		$data 	= array(
				'level' 	=> new Expression('`level` + ?', array($nodeSelectionInfo->level - $nodeMoveInfo->level + 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('level' => '`level` + ' . (int)($nodeSelectionInfo->level - $nodeMoveInfo->level + 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array( 'right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEFT) ========================= data - where +
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($nodeSelectionInfo->left + 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($nodeSelectionInfo->left + 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (RIGHT) ========================= data - where +
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($nodeSelectionInfo->left + 1 + $totalNode*2 - 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($nodeSelectionInfo->left + 1 + $totalNode*2 - 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node move (PARENT) ========================= data + where +
		/* $data 	= array(
				'parent' 	=> $nodeSelectionInfo->id
		);
		$this->tableGateway->update($data, array('id' => $nodeMoveInfo->id)); */
		
		$this->updateNodeMove($nodeMoveInfo->id, $nodeSelectionInfo->id);
	}
	
	public function moveBefore($nodeMoveID, $nodeSelectionID){
		// ========================= Detach branch =========================
		$totalNode	= $this->detachBranch($nodeMoveID);
		
		$nodeSelectionInfo	= $this->getNodeInfo(array('id' => $nodeSelectionID));
		$nodeMoveInfo		= $this->getNodeInfo(array('id' => $nodeMoveID));
		
		// ========================= Node on tree (LEFT) ========================= data + where -
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThanOrEqualTo('left', $nodeSelectionInfo->left);
		$where->greaterThan('right', 0);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('left' => $nodeSelectionInfo->left, 'right' => 0);
		$whereFormat      = array('%d', '%d');
		$operators        = array('left' => '>=','right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on tree (RIGHT) ========================= data + where -
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('right', $nodeSelectionInfo->left);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('right' => $nodeSelectionInfo->left);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEVEL) ========================= data - where +
		/* $where	= new Where();
		$where->lessThanOrEqualTo('right', 0);
		
		$data 	= array(
				'level' 	=> new Expression('`level` + ?', array($nodeSelectionInfo->level - $nodeMoveInfo->level))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('level' => '`level` + ' . (int)($nodeSelectionInfo->level - $nodeMoveInfo->level));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEFT) ========================= data - where +
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($nodeSelectionInfo->left))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($nodeSelectionInfo->left) );
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (RIGHT) ========================= data - where +
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($nodeSelectionInfo->left + $totalNode*2 - 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($nodeSelectionInfo->left + $totalNode*2 - 1) );
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node move (PARENT) ========================= data + where +
		/* $data 	= array(
				'parent' 	=> $nodeSelectionInfo->parent
		);
		$this->tableGateway->update($data, array('id' => $nodeMoveInfo->id)); */
		
		$this->updateNodeMove($nodeMoveInfo->id, $nodeSelectionInfo->parent);
	}
	
	public function moveAfter($nodeMoveID, $nodeSelectionID){
		// ========================= Detach branch =========================
		$totalNode	= $this->detachBranch($nodeMoveID);
		
		$nodeSelectionInfo	= $this->getNodeInfo(array('id' => $nodeSelectionID));
		$nodeMoveInfo		= $this->getNodeInfo(array('id' => $nodeMoveID));
		
		// ========================= Node on tree (LEFT) ========================= data + where -
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('left', $nodeSelectionInfo->right);
		$where->greaterThan('right', 0);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('left' => $nodeSelectionInfo->right, 'right' => 0);
		$whereFormat      = array('%d', '%d');
		$operators        = array('left' => '>','right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on tree (RIGHT) ========================= data + where -
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($totalNode * 2))
		);
		$where	= new Where();
		$where->greaterThan('right', $nodeSelectionInfo->right);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($totalNode * 2));
		$format           = array('%s');
		
		$where            = array('right' => $nodeSelectionInfo->right);
		$whereFormat      = array('%d');
		$operators        = array('right' => '>');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEVEL) ========================= data - where +
		/* $where	= new Where();
		$where->lessThanOrEqualTo('right', 0);
		
		$data 	= array(
				'level' 	=> new Expression('`level` + ?', array($nodeSelectionInfo->level - $nodeMoveInfo->level))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('level' => '`level` + ' . (int)($nodeSelectionInfo->level - $nodeMoveInfo->level));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (LEFT) ========================= data - where +
		/* $data 	= array(
				'left' 	=> new Expression('`left` + ?', array($nodeSelectionInfo->right + 1))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('left' => '`left` + ' . (int)($nodeSelectionInfo->right + 1));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node on branch (RIGHT) ========================= data - where +
		/* $data 	= array(
				'right' 	=> new Expression('`right` + ?', array($nodeSelectionInfo->right + $totalNode*2))
		);
		$this->tableGateway->update($data, $where); */
		
		$data             = array('right' => '`right` + ' . (int)($nodeSelectionInfo->right + $totalNode*2));
		$format           = array('%s');
		
		$where            = array('right' => 0);
		$whereFormat      = array('%d');
		$operators        = array('right' => '<=');
		
		$this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
		
		// ========================= Node move (PARENT) ========================= data + where +
		/* $data 	= array(
				'parent' 	=> $nodeSelectionInfo->parent
		);
		$this->tableGateway->update($data, array('id' => $nodeMoveInfo->id)); */
		
		$this->updateNodeMove($nodeMoveInfo->id, $nodeSelectionInfo->parent);
		
	}
	private function updateNodeMove($id, $parent){
	    $data             = array('parent' => $parent);
	    $format           = array('%d');
	    
	    $where            = array('id' => $id);
	    $whereFormat      = array('%d');
	    $operators        = array('id' => '=');
	    
	    $this->updateNotEqual($data, $where,$format,$whereFormat,$operators);
	}
	public function moveUp($nodeID, $options = null){
		$nodeSelection	= $this->listNodes(array('id' => $nodeID), array('task' => 'move-up'));
		if(!empty($nodeSelection)) $this->moveBefore($nodeID, $nodeSelection->id);
	}

	public function moveDown($nodeID, $options = null){
		$nodeSelection	= $this->listNodes(array('id' => $nodeID), array('task' => 'move-down'));
		if(!empty($nodeSelection)) $this->moveAfter($nodeID, $nodeSelection->id);
	}

// 	public function updateNode($data, $nodeID, $nodeParentID = null, $options = null){
	public function updateNode($data, $node, $format = null, $options = null){
		if(!empty($node['parent'])){
			$nodeParentInfo	= $this->getNodeInfo(array('id' => $node['parent']));
			$nodeInfo		= $this->getNodeInfo(array('id' => $node['id']));
			if(!empty($nodeParentInfo) && $nodeInfo->parent != $nodeParentInfo->id) {
				$this->moveRight($node['id'], $node['parent']);
			}
		}
		$where            = array('id' => $node['id']);
		$where_format     = array('%d');
		// $format - parent - level - left - right
		$format = array_merge($format, array('%d','%d','%d','%d'));
		$this->update($this->_table, $data, $where,$format,$where_format);
	}
	
	public function removeNode($nodeID, $options){
		switch ($options['type']) {
			case 'only':
				$this->removeNodeOnly($nodeID);
				break;
			case 'branch':
			default:
				$this->removeBranch($nodeID);
				break;
		}
		
	}
	
	public function removeBranch($nodeID){
		$this->detachBranch($nodeID, array('task' => 'remove-node') );
	}
	
	public function removeNodeOnly($nodeID){
		$nodeInfo	= $this->getNodeInfo(array('id' => $nodeID));
		$nodes		= $this->listNodes($nodeInfo, array('task' => 'list-childs'));
		
		if(!empty($nodes)){
			foreach ($nodes as $node){
				$this->moveRight($node->id, $nodeInfo->parent);
			}
		}
		
		$this->removeBranch($nodeID);
	}
	
}