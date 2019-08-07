@extends('layouts.app')

@section('content')
	<div class="container">
		<h4 class="center">Editar Cidade</h4>
		<div class="row">
			<nav>
				<div class="nav-wrapper blue-grey lighten-3">
					<div class="col s12">
					<a href="{{route('admin.principal')}}" class="breadcrumb">Início</a>
					<a href="{{route('admin.cidades')}}" class="breadcrumb">Lista de Cidades</a>
					<a class="breadcrumb">Editar Cidade</a>
					</div>
				</div>
			</nav>
		</div>
		<div class="row">
			<form action="{{route('admin.cidades.atualizar', $registro->id)}}" method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="put">
				@include('admin.cidades._form')
				<button class="btn blue-grey">Atualizar</button>
			</form>
		</div>
	</div>

@endsection	