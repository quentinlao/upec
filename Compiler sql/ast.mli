(* Syntaxe abstraite *)
type expr
type caseExpr
type caseCond
type cond
type predicate
type joinop
type column_def
type column
type projection
type source
type query

module R : Relation.S

(* Constructeurs d'expressions *)
val idExpr : string -> expr
val attributeExpr : string -> string -> expr
val parentheseExpr : expr -> expr
val intExpr : int -> expr 
val floatExpr : float -> expr
val addExpr :  expr -> expr -> expr
val subExpr : expr -> expr -> expr
val negExpr :  expr -> expr 
val mulExpr :  expr -> expr -> expr
val divExpr :  expr -> expr -> expr
val ppipeExpr :  expr -> expr -> expr
val stringExpr : string -> expr
val lowerExpr  :  expr -> expr
val upperExpr  :  expr -> expr
val substringExpr  :  expr -> expr -> expr -> expr
val caseExprthen : expr -> caseExpr -> expr
val caseExprthenelse  : expr -> caseExpr -> expr -> expr
val caseCondthen  : caseCond -> expr
val caseCondthenelse  : caseCond -> expr -> expr


(* Constructeurs de caseExpr*)
val norecCaseExpr : expr -> expr -> caseExpr
val recCaseExpr  : expr -> expr -> caseExpr -> caseExpr

(* Constructeurs de caseCond*)
val norecCaseCond : cond -> expr -> caseCond
val recCaseCond : cond -> expr -> caseCond -> caseCond

(* Constructeurs de condition *)
val predicateCond  : predicate -> cond
val negationCond  : cond -> cond
val andCond : cond -> cond -> cond
val orCond  : cond -> cond -> cond
val istrueCond : cond -> cond
val isnottrueCond : cond -> cond
val isfalseCond : cond -> cond
val isnotfalseCond  : cond -> cond
val isunknownCond : cond -> cond
val isnotunknownCond : cond -> cond

(* Constructeurs de predicate *)

val parenthesePred  : cond -> predicate
val eqPred  : expr -> expr -> predicate
val neqPred  : expr -> expr -> predicate
val ltPred  : expr -> expr -> predicate
val gtPred  : expr -> expr -> predicate
val lePred  : expr -> expr -> predicate
val gePred  : expr -> expr -> predicate
val betweenPred  : expr -> expr -> expr -> predicate
val notbetweenPred  :  expr -> expr -> expr -> predicate
val isnullPred  :  expr -> predicate
val isnotnullPred :  expr -> predicate


(*Constructeurs de join *)

val join : joinop
val innerjoin : joinop
val leftjoin : joinop
val rightjoin : joinop
val fulljoin : joinop
val leftouterjoin : joinop
val rightouterjoin : joinop
val fullouterjoin : joinop


(*Constructeurs de column_def *)

val exprCol : expr -> column_def
val expridCol : expr -> string -> column_def


(* Constructeurs de column *)

val norecCol : column_def -> column
val recCol  : column_def -> column -> column


(*Constructeurs de projection *)

val asteriskProj : projection
val columnProj  : column -> projection 

(*Constructeurs de source *)

val idSource  : string -> source
val parentheseSource  : query -> source
val commaSource  : source -> source -> source 
val crossjoinSource  :  source -> source -> source 
val joinonSource :  source -> joinop -> source -> cond -> source 

(* Constructeurs de query*)

val selectQuery : projection -> source -> query 
val selectwhereQuery :  projection -> source -> cond  -> query 
val selectallQuery :  projection -> source -> query 
val selectallwhereQuery  :  projection -> source -> cond  -> query 
val selectdistinctQuery  :  projection -> source -> query 
val selectdistinctwhereQuery  :  projection -> source -> cond  -> query 

val string_of_query : query -> string

val eval_query : (R.relation * R.attribute Env.env) Env.env -> query -> R.relation * R.attribute Env.env