open Value

module R = Relation.Make(Value)

type expr =
	|IdExpr of string
	|AttributExpr of string * string
	|ParentheseExpr of expr
	|IntegerExpr of int
	|FloatExpr of float
	|AdditionExpr of expr * expr
	|SubstractionExpr of expr * expr
	|MultiplicationExpr of expr * expr
	|DivisionExpr of expr * expr
	|NegationExpr of expr
	|StringExpr of string
	|PPipeExpr of expr * expr
	|LowerExpr of expr
	|UpperExpr of expr
	|SubstringExpr of expr * expr * expr
	|CaseExprThen of expr * caseExpr
	|CaseExprThenElse of expr * caseExpr * expr
	|CaseCondThen of  caseCond
	|CaseCondThenElse of caseCond * expr
and caseExpr =
	|NoRecCaseExpr of expr * expr
	|RecCaseExpr of expr * expr * caseExpr
and caseCond = 
	|NoRecCaseCond of cond * expr
	|RecCaseCond of cond * expr * caseCond
and  cond =
	|PredicateCond of predicate
	|NegationCond of cond
	|AndCond of cond * cond
	|OrCond of cond * cond
	|IsTrueCond of cond
	|IsNotTrueCond of cond
	|IsFalseCond of cond
	|IsNotFalseCond of cond
	|IsUnknownCond of cond
	|IsNotUnknownCond of cond
and predicate =
	|ParenthesePred of cond
	|EgalitePred of expr * expr
	|DifferencePred of expr * expr
	|InferieurStrictPred of expr * expr
	|SuperieurStrictPred of expr * expr
	|InferieurEgalPred of expr * expr
	|SuperieurEgalPred of expr * expr
	|BetweenPred of expr * expr * expr
	|NotBetweenPred of expr * expr * expr
	|IsNullPred of expr
	|IsNotNullPred of expr

type joinop = 
	| Join
	| LeftJoin
	| RightJoin
	| FullJoin
	| InnerJoin
	| LeftOuterJoin
	| RightOuterJoin
	| FullOuterJoin

type column_def = 
	|ExprCol of expr
	|ExprIdCol of expr * string

type column =
	|NoRecColumn of column_def
	|RecColumn of column_def * column

type projection =
	|AsteriskProj
	|ColumnProj of column

type source = 
	|IdSource of string
	|ParentheseSource of query
	|CommaSource of source * source
	|CrossJoinSource of source * source
	|JoinOnSource of source * joinop * source * cond
and query = 
	|SelectQuery of projection* source
	|SelectWhereQuery of projection* source* cond
	|SelectAllQuery of projection* source
	|SelectAllWhereQuery of projection* source * cond
	|SelectDistinctQuery of projection* source
	|SelectDistinctWhereQuery of projection* source * cond


 (*Constructeurs d'expr*)
let idExpr id = IdExpr id 
let attributeExpr id1 id2 = AttributExpr (id1,id2)
let parentheseExpr e = ParentheseExpr e
let intExpr i = IntegerExpr i
let floatExpr f = FloatExpr f
let addExpr e1 e2 = AdditionExpr (e1,e2)
let subExpr e1 e2 = SubstractionExpr (e1,e2)
let negExpr e1 = NegationExpr e1
let mulExpr e1 e2 = MultiplicationExpr (e1,e2)
let divExpr e1 e2 = DivisionExpr (e1,e2)
let ppipeExpr e1 e2 = PPipeExpr (e1,e2)
let stringExpr s = StringExpr s 
let lowerExpr e = LowerExpr e
let upperExpr e = UpperExpr e
let substringExpr e1 e2 e3 = SubstringExpr (e1,e2,e3)
let caseExprthen e ce = CaseExprThen (e,ce)
let caseExprthenelse e1 ce e2 = CaseExprThenElse(e1,ce,e2)
let caseCondthen cc = CaseCondThen (cc)
let caseCondthenelse cc e = CaseCondThenElse(cc,e)

(* Constructeurs de caseExpr*)
let norecCaseExpr e1 e2 = NoRecCaseExpr(e1,e2)
let recCaseExpr e1 e2 ce = RecCaseExpr (e1,e2,ce)

(* Constructeurs de caseCond*)
let norecCaseCond c e = NoRecCaseCond (c,e)
let recCaseCond c e cc = RecCaseCond  (c,e,cc)

(* Constructeurs de cond*)

let predicateCond p = PredicateCond p
let negationCond c = NegationCond c
let andCond c1 c2 = AndCond (c1,c2)
let orCond c1 c2 = OrCond(c1,c2)
let istrueCond c = IsTrueCond c
let isnottrueCond c = IsNotTrueCond c
let isfalseCond c = IsFalseCond  c
let isnotfalseCond c = IsNotFalseCond c
let isunknownCond c= IsUnknownCond c
let isnotunknownCond c	= IsNotUnknownCond c


(*Constructeurs de predicate *)

let parenthesePred c = ParenthesePred c
let eqPred e1 e2 = EgalitePred (e1,e2)
let neqPred e1 e2 = DifferencePred (e1,e2)
let ltPred e1 e2 = InferieurStrictPred (e1,e2)
let gtPred e1 e2 = SuperieurStrictPred (e1,e2)
let lePred e1 e2 = InferieurEgalPred (e1,e2)
let gePred e1 e2 = SuperieurEgalPred (e1,e2)
let betweenPred e1 e2 e3 = BetweenPred (e1,e2,e3)
let notbetweenPred e1 e2 e3 = NotBetweenPred (e1,e2,e3)
let isnullPred e = IsNullPred e
let isnotnullPred e = IsNotNullPred e

(*Constructeurs de joinop *)

let join = Join
let innerjoin = InnerJoin
let leftjoin = LeftJoin
let rightjoin = RightJoin
let fulljoin = FullJoin
let leftouterjoin = LeftOuterJoin
let rightouterjoin = RightOuterJoin
let fullouterjoin = FullOuterJoin


(*Constructeurs de column_def *)

let exprCol e = ExprCol e
let expridCol e id = ExprIdCol (e,id)


(* Constructeurs de column *)

let norecCol c = NoRecColumn c
let recCol c next = RecColumn (c,next)


(*Constructeurs de projection *)

let asteriskProj = AsteriskProj
let columnProj c = ColumnProj c

(*Constructeurs de source *)

let idSource s = IdSource s
let parentheseSource q = ParentheseSource q
let commaSource s1 s2 = CommaSource (s1,s2)
let crossjoinSource s1 s2 = CrossJoinSource (s1,s2)
let joinonSource s1 j s2 c = JoinOnSource (s1,j,s2,c)

(* Constructeurs de query*)

let selectQuery p s = SelectQuery (p,s)
let selectwhereQuery p s c = SelectWhereQuery (p,s,c)
let selectallQuery p s = SelectAllQuery (p,s)
let selectallwhereQuery p s c = SelectAllWhereQuery (p,s,c)
let selectdistinctQuery p s = SelectDistinctQuery (p,s)
let selectdistinctwhereQuery p s c = SelectDistinctWhereQuery (p,s,c)

(*----------------------------------------------------------------STRING_OF---------------------------------------------------------------------*)

let rec string_of_expr e = match e with
  |IdExpr id -> Printf.sprintf "%s" id
  |AttributExpr (id1,id2) -> Printf.sprintf "%s.%s " id1 id2
  |ParentheseExpr e -> Printf.sprintf "( %s )" (string_of_expr e)
  |IntegerExpr i -> Printf.sprintf "%s" (string_of_int i)
  |FloatExpr f -> Printf.sprintf "%s" (string_of_float f)
  |AdditionExpr (e1,e2) -> Printf.sprintf "%s + %s" (string_of_expr e1) (string_of_expr e2)
  |SubstractionExpr (e1,e2) -> Printf.sprintf "%s - %s" (string_of_expr e1) (string_of_expr e2)
  |MultiplicationExpr (e1,e2) -> Printf.sprintf "%s * %s" (string_of_expr e1) (string_of_expr e2) 
  |DivisionExpr (e1,e2) -> Printf.sprintf "%s / %s" (string_of_expr e1) (string_of_expr e2) 
  |NegationExpr e ->  Printf.sprintf "- %s" (string_of_expr e)   
  |StringExpr s -> Printf.sprintf "%s" s
  |PPipeExpr (e1,e2) -> Printf.sprintf "%s || %s" (string_of_expr e1) (string_of_expr e2) 
  |LowerExpr e -> Printf.sprintf "LOWER ( %s ) " (string_of_expr e)
  |UpperExpr e -> Printf.sprintf "UPPER ( %s ) " (string_of_expr e)
  |SubstringExpr (e1,e2,e3) -> Printf.sprintf "SUBSTRING ( %s FROM %s FOR %s )" (string_of_expr e1) (string_of_expr e2) (string_of_expr e3)
  |CaseExprThen (e,ce) -> Printf.sprintf "CASE %s %s END" (string_of_expr e) (string_of_caseExpr ce)
  |CaseExprThenElse (e1,ce,e2) -> Printf.sprintf "CASE %s %s ELSE %s END" (string_of_expr e1) (string_of_caseExpr ce) (string_of_expr e2)
  |CaseCondThen  cc -> Printf.sprintf "CASE %s END" (string_of_caseCond cc)
  |CaseCondThenElse (cc,e) -> Printf.sprintf "CASE %s ELSE %s END" (string_of_caseCond cc) (string_of_expr e)

and string_of_caseExpr ce = match ce with
	|NoRecCaseExpr (e1,e2) -> Printf.sprintf "WHEN %s THEN %s" (string_of_expr e1) (string_of_expr e2)
	|RecCaseExpr (e1,e2,ce) -> Printf.sprintf "WHEN %s THEN %s %s" (string_of_expr e1) (string_of_expr e2) (string_of_caseExpr ce)

and string_of_caseCond cc = match cc with 
	|NoRecCaseCond (c,e) ->  Printf.sprintf "WHEN %s THEN %s" (string_of_cond c) (string_of_expr e)
	|RecCaseCond (c,e,cc) -> Printf.sprintf "WHEN %s THEN %s %s" (string_of_cond c) (string_of_expr e) (string_of_caseCond cc)

and  string_of_cond c = match c with 
	|PredicateCond p -> string_of_predicate p
	|NegationCond c -> Printf.sprintf "NOT %s " (string_of_cond c)
	|AndCond (c1,c2) -> Printf.sprintf "%s AND %s" (string_of_cond c1) (string_of_cond c2)
	|OrCond (c1,c2) -> Printf.sprintf "%s OR %s " (string_of_cond c1) (string_of_cond c2)
	|IsTrueCond c -> Printf.sprintf "%s IS TRUE" (string_of_cond c)
	|IsNotTrueCond c -> Printf.sprintf " %s IS NOT TRUE" (string_of_cond c)
	|IsFalseCond c -> Printf.sprintf " %s IS FALSE" (string_of_cond c)
	|IsNotFalseCond c -> Printf.sprintf " %s IS NOT FALSE" (string_of_cond c)
	|IsUnknownCond c -> Printf.sprintf " %s IS UNKNOWN" (string_of_cond c)
	|IsNotUnknownCond c -> Printf.sprintf " %s IS NOT UNKNOWN" (string_of_cond c)

and string_of_predicate p = match p with
	|ParenthesePred c->  Printf.sprintf " ( %s ) "   (string_of_cond c)
	|EgalitePred (e1,e2) -> Printf.sprintf "%s = %s " (string_of_expr e1) (string_of_expr e2)
	|DifferencePred (e1,e2) -> Printf.sprintf "%s <> %s " (string_of_expr e1) (string_of_expr e2)
	|InferieurStrictPred (e1,e2) -> Printf.sprintf "%s < %s " (string_of_expr e1) (string_of_expr e2)
	|SuperieurStrictPred (e1,e2) -> Printf.sprintf "%s > %s " (string_of_expr e1) (string_of_expr e2)
	|InferieurEgalPred (e1,e2) -> Printf.sprintf "%s <= %s " (string_of_expr e1) (string_of_expr e2)
	|SuperieurEgalPred (e1,e2) -> Printf.sprintf "%s >= %s " (string_of_expr e1) (string_of_expr e2)
	|BetweenPred  (e1,e2,e3) ->  Printf.sprintf "%s BETWEEN %s AND %s " (string_of_expr e1) (string_of_expr e2) (string_of_expr e3)
	|NotBetweenPred (e1,e2,e3) -> Printf.sprintf  "%s NOT BETWEEN %s AND %s " (string_of_expr e1) (string_of_expr e2) (string_of_expr e3)
	|IsNullPred e -> Printf.sprintf "%s IS NULL" (string_of_expr e)
	|IsNotNullPred  e-> Printf.sprintf "%s IS NOT NULL" (string_of_expr e)


let string_of_joinop j = match j with
	| Join -> Printf.sprintf " JOIN "
	| LeftJoin -> Printf.sprintf " LEFT JOIN"
	| RightJoin -> Printf.sprintf " RIGHT JOIN "
	| FullJoin -> Printf.sprintf " FULL JOIN "
	| InnerJoin -> Printf.sprintf " INNER JOIN "
	| LeftOuterJoin -> Printf.sprintf " LEFT OUTER JOIN "
	| RightOuterJoin -> Printf.sprintf " RIGHT OUTER JOIN "
	| FullOuterJoin -> Printf.sprintf " FULL OUTER JOIN "


let string_of_column_def c = match c with
	|ExprCol e ->  Printf.sprintf "%s" (string_of_expr e) 
	|ExprIdCol (e,id) ->  Printf.sprintf "%s AS %s" (string_of_expr e)  id


let rec string_of_column  c = match c with 
	|NoRecColumn col -> Printf.sprintf "%s" (string_of_column_def col) 
	|RecColumn  (col,next) ->  Printf.sprintf "%s, %s" (string_of_column_def col) (string_of_column next)

let string_of_projection p = match p with
	|AsteriskProj -> Printf.sprintf  "*"
	|ColumnProj col -> Printf.sprintf "%s" (string_of_column col)


let rec string_of_query q = match q with
	|SelectQuery (p,s) -> Printf.sprintf "SELECT %s FROM %s" (string_of_projection p) (string_of_source s)
	|SelectWhereQuery (p,s,c) -> Printf.sprintf "SELECT %s FROM %s WHERE %s" (string_of_projection p) (string_of_source s) (string_of_cond c)
	|SelectAllQuery  (p,s) -> Printf.sprintf "SELECT ALL %s FROM %s" (string_of_projection p) (string_of_source s)
	|SelectAllWhereQuery(p,s,c) -> Printf.sprintf "SELECT ALL %s FROM %s WHERE %s" (string_of_projection p) (string_of_source s) (string_of_cond c)
	|SelectDistinctQuery (p,s) -> Printf.sprintf "SELECT DISTINCT %s FROM %s" (string_of_projection p) (string_of_source s)
	|SelectDistinctWhereQuery (p,s,c) -> Printf.sprintf "SELECT DISTINCT %s FROM %s WHERE %s" (string_of_projection p) (string_of_source s) (string_of_cond c)
and string_of_source s = match s with
	|IdSource s-> Printf.sprintf "%s" s
	|ParentheseSource q -> Printf.sprintf "( %s )" (string_of_query q)
	|CommaSource  (s1,s2) -> Printf.sprintf "%s , %s" (string_of_source s1) (string_of_source s2)
	|CrossJoinSource (s1,s2) -> Printf.sprintf "%s CROSS JOIN %s" (string_of_source s1) (string_of_source s2)
	|JoinOnSource (s1,j,s2,c) -> Printf.sprintf "%s %s %s ON %s" (string_of_source s1) (string_of_joinop j) (string_of_source s2) (string_of_cond c)


(*---------------------------------------------------------------------------------------------------------------------------------------------*)


let rec get_attribut_name_option env str = match env with
		| [] -> ""
		| (s, _) :: next -> let split = String.split_on_char '.' s in
			match split with
			| table :: id :: [] -> if String.equal str id then s else get_attribut_name_option next str
			| _ -> failwith "unknown" 

let rec remove_ambiguity env id = 												
	match get_attribut_name_option env id  with
	| "" -> id
	| s -> s

let isnull e = match e with
		|None -> true
		|_ -> false


let rec get_domainExpr env expr relation = match expr with 
	|IdExpr id -> ( match Env.find (remove_ambiguity env id) env with
						 | Some(v) -> R.domain relation v
						 | None -> failwith (Printf.sprintf "Error unknown domain: %s" id))
	|AttributExpr (id1,id2) -> let x = id1 ^ "." ^ id2 in
						   			(match Env.find x env with
						   			| Some(v) -> R.domain relation v
						   			| None -> failwith (Printf.sprintf "Error: unknown attribute: %s" x))
	|IntegerExpr i -> DInt
	|FloatExpr f -> DFloat
	|StringExpr s -> DVChar
	|ParentheseExpr e -> get_domainExpr env e relation
	|AdditionExpr (e1,e2) | SubstractionExpr (e1,e2) | MultiplicationExpr (e1,e2) | DivisionExpr (e1,e2) ->
	 			(match (get_domainExpr env e1 relation),(get_domainExpr env e2 relation) with
						|DInt , DInt -> DInt
						|DFloat , DFloat -> DFloat
						|DInt , DFloat -> DFloat
						|DFloat , DInt -> DFloat
						|_ , _ -> failwith (Printf.sprintf "float or int only"))
	|NegationExpr e -> (match get_domainExpr env e relation with
						|DVChar -> failwith (Printf.sprintf "float and int only")
						|d -> d )
	|PPipeExpr (e1,e2) -> (match (get_domainExpr env e1 relation),(get_domainExpr env e2 relation) with
							|DVChar , DVChar -> DVChar
							|_,_ -> failwith (Printf.sprintf "varchar only") )
	|LowerExpr e |UpperExpr e -> (match (get_domainExpr env e relation) with
							|DVChar -> DVChar
							|_-> failwith (Printf.sprintf "varchar only") )
	|SubstringExpr (e1,e2,e3) -> (match (get_domainExpr env e1 relation),(get_domainExpr env e2 relation), (get_domainExpr env e3 relation) with
							|DVChar , DInt, DInt -> DVChar
							|_,_,_ -> failwith (Printf.sprintf "varchar,int,int only"))
	|CaseExprThen (e,ce) -> (match ce with
								|NoRecCaseExpr (e1,e2) -> get_domainExpr env e2 relation
								|RecCaseExpr (e1,e2,ce1) -> get_domainExpr env e2 relation)
 	|CaseExprThenElse (e1,ce,e2) -> get_domainExpr env e2 relation
  	|CaseCondThen  cc -> (match cc with
							|NoRecCaseCond (c,e) -> get_domainExpr env e relation
							|RecCaseCond (c,e,cc1) -> get_domainExpr env e relation)
  	|CaseCondThenElse (cc,e) -> get_domainExpr env e relation



(*----------------------------------------------------EVAL_EXPR --------------------------------------------------------------*)
let rec eval_expr_option env expr t = match expr with
	|IdExpr id ->  ( match Env.find (remove_ambiguity env id) env with
    				 | Some(v) -> R.attribute v t 
    				 | None -> failwith (Printf.sprintf "Error: unknown value option: %s" id))
  
	|AttributExpr (id1,id2) -> let x = id1 ^ "." ^ id2 in
								   (match Env.find x env with
									| Some(v) -> R.attribute v t
									| None -> failwith (Printf.sprintf "Error: unknown attribute: %s" x))
	|ParentheseExpr e -> eval_expr_option env  e t
	|IntegerExpr i -> Some (VInt i)
	|FloatExpr f -> Some (VFloat f)
	|AdditionExpr (e1,e2) -> Some ( add (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t )) )
	|SubstractionExpr (e1,e2) -> Some ( sub (getValueExpr (eval_expr_option env e1 t )) (getValueExpr (eval_expr_option env e2 t)) )
	|MultiplicationExpr (e1,e2) -> Some ( mul (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) )
	|DivisionExpr (e1,e2) -> Some ( div (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) )
	|NegationExpr e -> Some ( neg (getValueExpr (eval_expr_option env e t)) )
	|StringExpr s -> Some (VVChar s)
	|PPipeExpr (e1,e2) -> Some ( concat (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) )
	|LowerExpr e -> Some ( lower (getValueExpr (eval_expr_option env e t)) )
	|UpperExpr e -> Some ( upper (getValueExpr (eval_expr_option env e t)) )
	|SubstringExpr (e1,e2,e3) -> Some ( substring (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) (getValueExpr (eval_expr_option env e3 t)))
	|CaseExprThen (e,ce) -> (match (eval_caseExpr env ce e t) with
									|Some x -> eval_expr_option env x t 
									|None -> failwith (Printf.sprintf "unknown expr case "))
 	|CaseExprThenElse (e1,ce,e2) -> (match (eval_caseExpr env ce e1 t)with
									|Some x -> eval_expr_option env x t 
									|None -> eval_expr_option env e2 t)
  	|CaseCondThen  cc -> (match (eval_caseCond env cc t) with
									|Some x -> eval_expr_option env x t 
									|None -> failwith (Printf.sprintf "unknown cond case")) 
  	|CaseCondThenElse (cc,e) -> (match (eval_caseCond env cc t) with
									|Some x -> eval_expr_option env x t 
									|None ->  eval_expr_option env e t) 
and  getValueExpr e = 
	match e with
		|Some(v) -> v
		|_ -> failwith (Printf.sprintf "No Value")

and eval_caseExpr env ce expr t =  match ce with
	|NoRecCaseExpr (e1,e2) -> if (getValueExpr (eval_expr env expr t)) = (getValueExpr (eval_expr env e1 t))  then Some e2 else None 
	|RecCaseExpr (e1,e2,ce1) -> if (getValueExpr (eval_expr env expr t)) = (getValueExpr (eval_expr env e1 t)) then Some e2 else (eval_caseExpr env ce1 expr t) 

and eval_caseCond env cc t= match cc with 
	|NoRecCaseCond (c,e) ->  if(eval_cond_pred env c t) then Some e else None 
	|RecCaseCond (c,e,cc1) -> if (eval_cond_pred env c t) then Some e else (eval_caseCond env cc1 t)

and eval_expr env expr = fun t -> eval_expr_option env expr t 

(*----------------------------------------------------EVAL_CONDITION_ET_PREDICATE--------------------------------------------------------------*)
and  eval_cond_pred env cond t = match cond with
	|PredicateCond p -> eval_predicate env p t
	|NegationCond c -> not (eval_cond_pred env cond t)
	|AndCond (c1,c2) -> (eval_cond_pred env c1 t) && (eval_cond_pred env c2 t)
	|OrCond (c1,c2) -> (eval_cond_pred env c1 t) || (eval_cond_pred env c2 t)
	|IsTrueCond c -> eval_cond_pred env c t
	|IsNotTrueCond c -> not (eval_cond_pred env c t)
	|IsFalseCond c -> not (eval_cond_pred env c t)
	|IsNotFalseCond c -> eval_cond_pred env c t
	|IsUnknownCond c -> unknown_cond env c t
	|IsNotUnknownCond c -> not (unknown_cond env c t)

and eval_predicate env pred t = match pred with
	|ParenthesePred c->  eval_cond_pred env c t
	|EgalitePred (e1,e2) -> eq (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|DifferencePred (e1,e2) -> neq (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|InferieurStrictPred (e1,e2) -> lt (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|SuperieurStrictPred (e1,e2) -> gt (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|InferieurEgalPred (e1,e2) -> le (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|SuperieurEgalPred (e1,e2) ->  ge (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t))
	|BetweenPred  (e1,e2,e3) ->  between  (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) (getValueExpr (eval_expr_option env e3 t))
	|NotBetweenPred (e1,e2,e3) -> notbetween  (getValueExpr (eval_expr_option env e1 t)) (getValueExpr (eval_expr_option env e2 t)) (getValueExpr (eval_expr_option env e3 t))
	|IsNullPred e -> isnull (eval_expr_option env e t)
	|IsNotNullPred  e-> not (isnull (eval_expr_option env e t))

and unknown_cond env cond t = match cond with
	|PredicateCond p -> unknown_pred env p t
	|AndCond (c1,c2) -> if (unknown_cond env c1 t) && ((unknown_cond env c2 t) <> true) then 
							(match (eval_cond_pred env c2 t) with
							  |false -> false
							  |_ -> true)
						else if (unknown_cond env c2 t) && ((unknown_cond env c1 t) <> true) then
							(match (eval_cond_pred env c1 t) with
							|false -> false
							  |_ -> true)
						else if (unknown_cond env c2 t <> true) && ((unknown_cond env c1 t) <> true) then false
						else true
	|OrCond (c1,c2) -> if (unknown_cond env c1 t) && ((unknown_cond env c2 t) <> false) then 
							(match (eval_cond_pred env c2 t) with
							  |true -> false
							  |_ -> true)
						else if (unknown_cond env c2 t) && ((unknown_cond env c1 t) <> false) then
							(match (eval_cond_pred env c1 t) with
							  |true -> false
							  |_ -> true)
						else if (unknown_cond env c2 t <> true) && ((unknown_cond env c1 t) <> true) then false
						else true
	|NegationCond c	|IsTrueCond c |IsNotTrueCond c |IsFalseCond c |IsNotFalseCond c |IsUnknownCond c -> unknown_cond env c t  
	|IsNotUnknownCond c -> not (unknown_cond env c t)

and unknown_pred env pred t = match pred with
	|ParenthesePred c->  unknown_cond env c t
	|EgalitePred(e1,e2) |DifferencePred(e1,e2) |InferieurStrictPred(e1,e2) |SuperieurStrictPred(e1,e2) |InferieurEgalPred(e1,e2) |SuperieurEgalPred (e1,e2) -> (isnull (eval_expr_option env e1 t)) || (isnull (eval_expr_option env e2 t))
	|BetweenPred  (e1,e2,e3) |NotBetweenPred (e1,e2,e3) -> (isnull (eval_expr_option env e1 t)) || (isnull (eval_expr_option env e2 t)) ||  (isnull (eval_expr_option env e3 t))
	|IsNullPred e -> isnull (eval_expr_option env e t)
	|IsNotNullPred  e-> not (isnull (eval_expr_option env e t))

let eval_cond env cond = fun t -> eval_cond_pred env cond t 

(*----------------------------------------------------EVAL_PROJECTION--------------------------------------------------------------------------------*)
let rec get_attributeString env = match env with
		| [] -> []
		| (s, _) :: next -> [s] @ (get_attributeString next)

let rec transform_toColumnlist stringlist = match stringlist with
	| [] -> []
	| str :: next -> ExprCol(IdExpr str) :: (transform_toColumnlist next)
 
let rec column_to_ColumnType column =
		match column with
		| [] -> failwith "column error"
		| c :: [] -> NoRecColumn(c)
		| c :: next -> RecColumn(c, column_to_ColumnType next)

let eval_column_def env column relation l attribute newenv = match column with
	|ExprCol e -> (newenv, (l @ [(get_domainExpr env e relation), (eval_expr env e)]))
	|ExprIdCol (e,id) -> ((Env.add id attribute newenv), (l @ [(get_domainExpr env e relation), (eval_expr env e)]))

let rec eval_column_rec env column relation newenv l id = 
		match column with
		| NoRecColumn(c) -> eval_column_def env c relation l id newenv 		  
		| RecColumn(c, next) ->   (match c with
									| ExprCol(e) -> eval_column_rec env next relation newenv (l @ [(get_domainExpr env e relation, eval_expr env e)]) (id+1) 
									| ExprIdCol (e,idEx) -> eval_column_rec env next relation (Env.add idEx id newenv) (l @ [(get_domainExpr env e relation, eval_expr env e)]) (id+1)
								  )

let eval_column relation env column =  let newenv = Env.empty in let l = [] in let id = 0 
	in eval_column_rec env column relation newenv l id

let eval_proj env proj relation =
	let string_list = get_attributeString env in
	let column_list = column_to_ColumnType (transform_toColumnlist string_list) in
	match proj with
	| AsteriskProj -> eval_column relation env column_list
	| ColumnProj(c) -> eval_column relation env c


(*------------------------------------------------EVAL_SOURCE--------------------------------------------------------------------------------*)

let deuxtuples_cond env cond = (fun t1 t2 -> (eval_cond env cond) (R.append t1 t2)) (* sinon index_out_of_bounds *)

let rec getNbAttribut env = match env with
	| [] -> 0
	|  x ::  next -> 1 + getNbAttribut next

let rec shift_attribut env shift= match env with
		| [] -> []
		| (s, attribute) :: next -> (s, (attribute + shift)) :: (shift_attribut next shift)

let avoid_ambiguity r1 r2 env1 env2 =
	let nbEnv1 = getNbAttribut env1 in
	let newenv2 = shift_attribut env2 nbEnv1  in (env1, newenv2)

let rec rename env sourceName = match env with
		  | [] -> [] 
		  | (s, att) :: next -> ((sourceName ^ "." ^ s), att) :: (rename next sourceName)

let rec eval_source env source = match source with
	|IdSource id -> let x = Env.find id env in (match x with
												|Some(relation,attribut_env) -> (relation,rename attribut_env id)
												|_ -> failwith (Printf.sprintf "id or env error"))
	|ParentheseSource q -> eval_query env q
	|CommaSource (s1,s2) |CrossJoinSource (s1,s2) -> let res1 = eval_source env s1 in let res2 = eval_source env s2 in
														(match res1,res2 with
														|(r1,env1),(r2,env2) ->  let newenv1,newenv2 = avoid_ambiguity r1 r2 env1 env2
														in (R.crossjoin r1 r2, (Env.union newenv1 newenv2) ))
	|JoinOnSource (s1,join,s2,cond) -> (match join with
													| Join | InnerJoin -> eval_sourceJoinOn s1 s2 cond R.innerjoin env
													| LeftJoin |LeftOuterJoin -> eval_sourceJoinOn s1 s2 cond R.leftouterjoin env
													| RightJoin | RightOuterJoin -> eval_sourceJoinOn s1 s2 cond R.rightouterjoin env
													| FullJoin | FullOuterJoin -> eval_sourceJoinOn s1 s2 cond R.fullouterjoin env)

and eval_sourceJoinOn s1 s2 cond action env = let res1 = eval_source env s1 in let res2 = eval_source env s2 in
											  (match res1,res2 with
												|(r1,env1),(r2,env2) -> let newenv1,newenv2 = avoid_ambiguity r1 r2 env1 env2 in 
																		let cond_env = Env.union newenv1 newenv2 in 
																		(action (deuxtuples_cond cond_env cond) r1 r2, cond_env))

(*------------------------------------------------EVAL_QUERY------------------------------------------------------------------------------------*)

and getQueryResult env p s = 
		match p with
		|AsteriskProj -> let res_source = eval_source env s in 
							(match res_source with
								|(relation,attribute_env)-> let res_proj = eval_proj attribute_env p relation in
								(match res_proj with
									|(newenv,l)-> (R.projection l relation,attribute_env)))
		|ColumnProj c -> let res_source = eval_source env s in 
							(match res_source with
								|(relation,attribute_env)-> let res_proj = eval_proj attribute_env p relation in
								(match res_proj with
									|(newenv,l)-> (R.projection l relation,newenv)))

and getQueryWhereResult env  p s c = 
		let res_source = eval_source env s in 
		(match res_source with
			|(relation,attribute_env)-> let res_proj = eval_proj attribute_env p relation in
										let res_cond = eval_cond attribute_env c in
			(match p with
		|AsteriskProj ->(match res_proj with
							|(newenv,l)-> (R.projection l (R.selection res_cond relation),attribute_env))
		|ColumnProj c -> (match res_proj with
							|(newenv,l)-> (R.projection l (R.selection res_cond relation),newenv))))

and eval_query env query = match query with
	|SelectQuery (p,s) |SelectAllQuery (p,s) -> getQueryResult env p s 
	|SelectWhereQuery (p,s,c) |SelectAllWhereQuery (p,s,c) ->  getQueryWhereResult env p s c
	|SelectDistinctQuery (p,s) -> (match getQueryResult env p s with
										|(relation,attribute_env)-> (R.distinct relation, attribute_env))
	|SelectDistinctWhereQuery (p,s,c) -> (match getQueryWhereResult env p s c with
										|(relation,attribute_env)-> (R.distinct relation, attribute_env))
											