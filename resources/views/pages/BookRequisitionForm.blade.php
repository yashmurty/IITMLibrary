@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Book Requisition Form</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/bookrequisitionform') }}">
                        {!! csrf_field() !!}

                        <!-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <!-- START - Type of Document -->
                        <h4>Type of Document</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType1" value="option1" checked>
                                    Books / Monographs 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType2" value="option2">
                                    Thesis 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType3" value="option3">
                                    Standards 
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType4" value="option1">
                                    Conference Proceedings 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType5" value="option2">
                                    Audio / Video Materials 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType6" value="option3">
                                    Hindi Book(s) 
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType7" value="option1">
                                    Technical Reports 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType8" value="option2">
                                    Patents 
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="optionsDocumentType" id="optionsDocumentType9" value="option3">
                                    Others 
                                  </label>
                                </div>
                            </div>
                        </div>
                        <!-- END - Type of Document -->
                        <hr>
                        <!-- START - Authors -->
                        <div class="form-group col-md-12">
                            <label for="inputAuthor">Author(s) (Personal/Corporate)</label>
                            <input type="text" class="form-control" id="inputAuthor" name="inputAuthor" placeholder="Please render the nams as found in the source" required>
                        </div>
                        <!-- END - Authors -->

                        <!-- START - Title -->
                        <div class="form-group col-md-12">
                            <label for="inputTitle">Title in full including subtitle, if any </label>
                            <input type="text" class="form-control" id="inputTitle" name="inputTitle" placeholder="Enter title with subtitle here" required>
                        </div>
                        <!-- END - Title -->

                        <!-- START - Publisher -->
                        <div class="form-group col-md-12">
                            <label for="inputPublisher">Publishers </label>
                            <input type="text" class="form-control" id="inputPublisher" name="inputPublisher" placeholder="In case of books">
                        </div>
                        <!-- END - Publisher -->

                        <!-- START - Agency -->
                        <div class="form-group col-md-12">
                            <label for="inputAgency">Issuing Agency and its address </label>
                            <input type="text" class="form-control" id="inputAgency" name="inputAgency" placeholder="In case of non-book documents">
                        </div>
                        <!-- END - Agency -->

                        <!-- START - ISBN -->
                        <div class="form-group col-md-12">
                            <label for="inputISBN">Document Number, if any </label>
                            <input type="text" class="form-control" id="inputISBN" name="inputISBN" placeholder="eg. ISBN, Report No., Patent No., etc">
                        </div>
                        <!-- END - ISBN -->

                        <!-- START - Volume -->
                        <div class="form-group col-md-12">
                            <label for="inputVolume">Vol./Edition No./Year </label>
                            <input type="text" class="form-control" id="inputVolume" name="inputVolume" placeholder="Volume / Edition Number / Year">
                        </div>
                        <!-- END - Volume -->

                        <!-- START - Price -->
                        <div class="form-group col-md-12">
                            <label for="inputPrice">Price </label>
                            <input type="text" class="form-control" id="inputPrice" name="inputPrice" placeholder="If known">
                        </div>
                        <!-- END - Price -->

                        <!-- START - SectionCatalogue -->
                        <div class="form-group col-md-12">
                            <label for="inputSectionCatalogue">Source for section catalogue / Books on approval from Display </label>
                            <input type="text" class="form-control" id="inputSectionCatalogue" name="inputSectionCatalogue" placeholder="">
                        </div>
                        <!-- END - SectionCatalogue -->

                        <!-- START - NumberOfCopies -->
                        <div class="form-group col-md-12">
                            <label for="inputNumberOfCopies">Number of copies required </label>
                            <input type="text" class="form-control" id="inputNumberOfCopies" name="inputNumberOfCopies" placeholder="">
                        <hr>
                        </div>
                        <!-- END - NumberOfCopies -->

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <i class="fa fa-btn fa-book"></i>| Recommend for Purchase
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
