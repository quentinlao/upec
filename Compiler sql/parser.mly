%{
	open Ast
%}

/* Déclaration des terminaux */
%token <int> INT
%token <float> FLOAT
%token <string> STRING
%token <string> ID
%token PLUS MINUS ASTERISK SLASH EQ NEQ LT GT LE GE PPIPE TERM
%token QQUOTE QUOTE DOT LPAR RPAR 
%token ALL AND AS BETWEEN BY CASE CROSS DISTINCT ELSE END FALSE FOR FROM FULL GROUP HAVING INNER IS JOIN LEFT LOWER NOT NULL ON OR OUTER RIGHT SELECT SUBSTRING THEN TRUE UNKNOWN UPPER WHEN WHERE
%token COMMA

/* Précédences des terminaux */
%left PPIPE 
%left PLUS MINUS
%left ASTERISK SLASH
%left EQ NEQ LT GT LE GE
%left OR
%left AND
%left NOT
%left IS
%nonassoc CROSS FULL INNER JOIN LEFT OUTER RIGHT
%nonassoc UMINUS 




%type <Ast.query> ansyn
%start ansyn

%%
ansyn :
	| TERM ansyn { $2 }
	| simple_query TERM  { $1 }

simple_query : 
	| SELECT projection FROM source { selectQuery $2 $4}
	| SELECT projection FROM source WHERE cond { selectwhereQuery $2 $4 $6}
	| SELECT ALL projection FROM source { selectallQuery $3 $5}
	| SELECT ALL projection FROM source WHERE cond { selectallwhereQuery $3 $5 $7}
	| SELECT DISTINCT projection FROM source {selectdistinctQuery $3 $5}
	| SELECT DISTINCT projection FROM source WHERE cond  {selectdistinctwhereQuery $3 $5 $7}

source : 
	| ID { idSource $1}
	| LPAR simple_query RPAR {parentheseSource $2}
	| source COMMA source { commaSource $1 $3}
	| source CROSS JOIN source { commaSource $1 $4}
	| source joinop source ON cond {joinonSource $1 $2 $3 $5}

projection :
	| ASTERISK {asteriskProj} 
	| column {columnProj $1}

column : 
	| column_def {norecCol $1}
	| column_def COMMA column { recCol $1 $3}

column_def : 
	| expr {exprCol $1}
	| expr AS ID {expridCol $1 $3}

joinop : 
	| JOIN {join}
	| INNER JOIN {innerjoin}
	| LEFT JOIN {leftjoin}
	| RIGHT JOIN {rightjoin}
	| FULL JOIN {fulljoin}
	| LEFT OUTER JOIN {leftouterjoin}
	| RIGHT OUTER JOIN {rightouterjoin}
	| FULL OUTER JOIN {fullouterjoin}

predicate :
	| LPAR cond RPAR {parenthesePred $2}
	| expr EQ expr { eqPred $1 $3}
	| expr NEQ expr { neqPred $1 $3}
	| expr LT expr {ltPred $1 $3}
	| expr GT expr {gtPred $1 $3}
	| expr LE expr {lePred $1 $3}
	| expr GE expr {gePred $1 $3}
	| expr BETWEEN expr AND expr {betweenPred $1 $3 $5}
	| expr NOT BETWEEN expr AND expr {notbetweenPred $1 $4 $6}
	| expr IS NULL { isnullPred $1}
	| expr IS NOT NULL {isnotnullPred $1}

cond : 
	| predicate {predicateCond $1}
	| NOT cond  {negationCond $2}
	| cond AND cond {andCond $1 $3}
	| cond OR cond  {orCond $1 $3}
	| cond IS TRUE  {istrueCond $1}
	| cond IS NOT TRUE {isnottrueCond $1}
	| cond IS FALSE {isfalseCond $1}
	| cond IS NOT FALSE {isnotfalseCond $1}
	| cond IS UNKNOWN {isunknownCond $1}
	| cond IS NOT UNKNOWN {isnotunknownCond $1}


expr :
	| ID { idExpr $1 }
	| ID DOT ID { attributeExpr $1 $3 }
	| LPAR expr RPAR { parentheseExpr $2}
	| INT { intExpr $1}
	| FLOAT {floatExpr $1}
	| expr PLUS expr {addExpr $1 $3}
	| expr MINUS expr {subExpr $1 $3}
	| expr ASTERISK expr { mulExpr $1 $3}
	| expr SLASH expr { divExpr $1 $3}
	| expr PPIPE expr { ppipeExpr $1 $3 }
	| MINUS expr { negExpr $2}
	| STRING { stringExpr $1}
	| LOWER LPAR expr RPAR  { lowerExpr $3 }
	| UPPER LPAR expr RPAR	{ upperExpr $3 }
	| SUBSTRING LPAR expr FROM expr FOR expr RPAR { substringExpr $3 $5 $7}
	| CASE expr caseExpr END { caseExprthen $2 $3}
	| CASE expr caseExpr ELSE expr END { caseExprthenelse $2 $3 $5}
	| CASE caseCond END { caseCondthen $2}
	| CASE caseCond ELSE expr END { caseCondthenelse $2 $4}

caseExpr :
	| WHEN expr THEN expr 	{ norecCaseExpr $2 $4}
	| WHEN expr THEN expr caseExpr { recCaseExpr $2 $4 $5}

caseCond :
	| WHEN cond THEN expr { norecCaseCond $2 $4}
	| WHEN cond THEN expr caseCond { recCaseCond $2 $4 $5}
%%