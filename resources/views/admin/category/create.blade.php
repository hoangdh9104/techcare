@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Category</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create category</h4>
                            <div class="card-header">
                                <form action="">
                                    <div class="form-group">
                                        <label for="">Icon</label>
                                        <div>
                                            <button class="btn btn-primary" data-selected-class="btn-danger"
                                                data-unselected-class="btn-info" role="iconpicker"></button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('type') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inative</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
