{
	open Parser
	exception Eof
}

(* Déclaration du dictionnaire (regexp -> terminal/token) *)

let alpha = ['a'-'z' 'A'-'Z'] | "é" | "è" 
let digit = ['0'-'9']
let expo = ['e' 'E' ] ['+' '-']? digit+

rule anlex = parse 
	| [' ' '\t' '\n' '\r' ]  | "--" [^ '\n']* '\n'  				{ anlex lexbuf (* Oubli des espacements et passages à la ligne *) }
	| '*'															{ ASTERISK }
	| '+' 															{ PLUS }
	| '-' 															{ MINUS }
	| '/' 															{ SLASH }
	| ''' 															{ QUOTE }
	| "||" 															{ PPIPE }
	| ','  															{ COMMA }
	| '\"'  														{ QQUOTE }
	| '.'														  	{ DOT }
	| '('															  { LPAR }
	| ')'															  { RPAR }
	| '='                     					{ EQ } 
  | "<>"                    					{ NEQ }
  | '<'			          							  { LT }
  | '>'	    		      								{ GT }
  | "<="					  									{ LE }
  | ">="			          							{ GE }
  | ';'														   	{ TERM }
  | "ALL" 					 								  { ALL }
  | "AND" 					  							  { AND }
  | "AS" 					 									  { AS }
  | "BETWEEN" 				  							{ BETWEEN }
  | "BY" 			        								{ BY }
  | "CASE"                            { CASE }
  | "CROSS"			          						{ CROSS }
  | "DISTINCT" 			      						{ DISTINCT }
  | "ELSE"                            { ELSE }
  | "END"                             { END }
  | "FALSE"  				  								{ FALSE }
  | "FOR"                     				{ FOR }
  | "FROM" 			          						{ FROM }
  | "FULL"		              					{ FULL }
  | "GROUP" 			   	  							{ GROUP }
  | "HAVING"  			      						{ HAVING }
  | "INNER" 				  								{ INNER }
  | "IS" 				      								{ IS }
  | "JOIN"					  								{ JOIN }
  | "LEFT" 					  								{ LEFT}
  | "LOWER" 				  								{ LOWER }
  | "NOT" 					  								{ NOT }
  | "NULL" 					  								{ NULL }	
  | "ON"  				      							{ ON }
  | "OR"					  									{ OR }
  | "OUTER" 				  								{ OUTER }
  | "RIGHT" 				  								{ RIGHT }
  | "SELECT" 			      							{ SELECT }
  | "SUBSTRING"				  							{ SUBSTRING }
  | "THEN"                            { THEN }
  | "TRUE"					  								{ TRUE }
  | "UNKNOWN" 			      						{ UNKNOWN }
  | "UPPER" 		          						{ UPPER }
  | "WHEN"                            { WHEN }
  | "WHERE"			          						{ WHERE }
  | alpha (alpha|digit)* as lxm | '\"' [^ '\"']*  '\"' as lxm        { ID lxm}
  | digit+ as lxm                         { INT (int_of_string lxm) }
  | digit+ '.' digit+? expo? as lxm | '.' digit+ expo? as lxm     { FLOAT (float_of_string lxm)}
  | ''' (([^ ''']| ''' ''')* as lxm)'''     { STRING lxm}
  | eof                      										{ raise Eof }
  | _  as lxm                										{ (* Pour tout autre caractère : message sur la sortie erreur + oubli *)
                               											Printf.eprintf "Unknown character '%c': ignored\n" lxm; flush stderr;
                               											anlex lexbuf
                           											}