<?php
/**
 * Backup model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct											_prepareInsertRow
 * create
 * restore
 *
 */

class Backup extends CModel
{
	/** @var string */    
	private $_format = 'compact';
	private $_queryMaxLength = 700;
	
	/**
	 * Class default constructor
	 * @param array $params Database connection parameters
	 */
	public function __construct($params = array())
    {
        parent::__construct($params);
    }
    
    /**
	 * Creates backup file
	 * @param string $table
	 * @return string
     */
	public function create($tables = '*')
    {
    	$sqlQuery = '';
    	$nl = "\n";
    
    	// Save all tables with our prefix
    	if($tables == '*'){
    		$tables = array();
    		$result = $this->_db->showTables();
    		if(is_array($result)){
	    		foreach($result as $key){
	    			if(preg_match('/'.CConfig::get('db.prefix').'(.+)/i', $key[0], $matches)){
	    				// Get table name without the prefix
	    				$tables[] = $matches[1];
	    			}
	    		}
    		}
    	}else{
    		$tables = is_array($tables) ? $tables : explode(',',$tables);
    	}
    
    	// Run cycle through list of tables
    	foreach($tables as $table){
    		$fullTableName = CConfig::get('db.prefix').$table;
    		
			// Table columns
			$columns = $this->_db->showColumns($table);
    		$numColumns = count($columns);
    
			// Drop table
    		$sqlQuery .= 'DROP TABLE IF EXISTS '.$fullTableName.';';
			
			// Create table
    		$result = $this->_db->customQuery('SHOW CREATE TABLE '.$fullTableName);
			if(isset($result[0]['Create Table'])){
				$sqlQuery .= $nl.$nl.$result[0]['Create Table'].';'.$nl.$nl;				
			}
    
    		// Insert values
			$result = $this->_db->select('SELECT * FROM '.$fullTableName);
			$totalResults = count($result);
			$insertQuery = '';
			
			if(is_array($result) && !empty($result)){
				$firstPart = 'INSERT INTO '.$fullTableName.' VALUES'.$nl;
				if($this->_format == 'compact'){					
					$insertQuery .= $firstPart;
					$rowsCount = 0;
					$queryLength = 0;
					foreach($result as $row){
						
						// Limit query length
						if($rowsCount < $totalResults && ++$queryLength >= $this->_queryMaxLength){
							$insertQuery = trim($insertQuery, ','.$nl);
							$insertQuery .= ';'.$nl;
							$insertQuery .= $firstPart;
							$queryLength = 0;
						}

						$insertQuery .= '(';						
						$insertQuery .= $this->_prepareInsertRow($row, $columns, $numColumns);						
						$insertQuery .= ')';
						$insertQuery .= ((++$rowsCount < $totalResults) ? ','.$nl : '');
					}
					$insertQuery .= ';'.$nl;					
				}else{					
					foreach($result as $row){
						$insertQuery .= $firstPart;
						$insertQuery .= '(';
						$insertQuery .= $this->_prepareInsertRow($row, $columns, $numColumns);						
						$insertQuery .= ');'.$nl;
					}
				}
			}
			
    		$sqlQuery .= $insertQuery.$nl.$nl.$nl;
    	}
		
    	return $sqlQuery;
    }
    
    /**
	 * Restores backup file
	 * @param string $sqlQuery
	 * @return bool
     */
    public function restore($sqlQuery = '')
    {
		$nl = "\n";
		$sqlArray = array();
		$sqlLength = strlen($sqlQuery);
		$pos = strpos($sqlQuery, ';');
		
		for($i=$pos; $i<$sqlLength; $i++){
			if($sqlQuery[0] == '#'){
				$sqlQuery = ltrim(substr($sqlQuery, strpos($sqlQuery, $nl)));
				$sqlLength = strlen($sqlQuery);
				$i = strpos($sqlQuery, ';')-1;
				continue;
			}
			if($sqlQuery[($i+1)] == $nl){
				for($j=($i+2); $j<$sqlLength; $j++){
					if(trim($sqlQuery[$j]) != ''){
						$next = substr($sqlQuery, $j, 6);
						if($next[0] == '#'){
							// Remove line  where the break position (#comment line)
							for($k=$j; $k<$sqlLength; $k++){
								if($sqlQuery[$k] == $nl) break;
							}
							$query = substr($sqlQuery, 0, $i+1);
							$sqlQuery = substr($sqlQuery, $k);
							// Join 2 parts of query
							$sqlQuery = $query.$sqlQuery;
							$sqlLength = strlen($sqlQuery);
							$i = strpos($sqlQuery, ';')-1;
							continue 2;
						}
						break;
					}
				}
				// Get last insert query
				if($next == ''){ 
					$next = 'insert';
				}
				if((preg_match('/create/i', $next)) || (preg_match('/insert/i', $next)) || (preg_match('/drop t/i', $next))){
					$next = '';
					$sqlArray[] = substr($sqlQuery, 0, $i);
					$sqlQuery = ltrim(substr($sqlQuery, $i+1));
					$sqlLength = strlen($sqlQuery);
					$i = strpos($sqlQuery, ';')-1;
				}
			}
		}
		
		$max = count($sqlArray);
		for($i=0; $i<$max; $i++){
			if(false === $this->_db->customExec($sqlArray[$i])){
				//echo $sqlArray[$i];
				//exit;
				return false;
			}
		}
		
		return true;
	}
	

	/**
	 * Prepares row values for INSERT sql statement
	 * @param array $row
	 * @param array $columns
	 * @param string $numColumns
	 * @return string
	 */
	private function _prepareInsertRow($row, $columns, $numColumns)
	{
		$insertValues = '';
		for($i=0; $i<$numColumns; $i++){
			$value = '';
			
			if(isset($row[$columns[$i]['Field']])){
				$value = $row[$columns[$i]['Field']];
				$value = addslashes($value);
				$value = preg_replace('/\n/', '\\n', $value);
			}
			
			if($value !== ''){
				$insertValues .= '"'.$value.'"';
			}else{
				if(isset($columns[$i]['Null']) && $columns[$i]['Null'] == 'YES'){
					$insertValues .= 'NULL';
				}else{
					$insertValues .= '""';
				}
			}
			
			if($i<($numColumns-1)) $insertValues .= ',';
		}
		
		return $insertValues;
	}
	
}

