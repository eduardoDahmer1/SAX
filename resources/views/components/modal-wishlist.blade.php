<div class="modal fade" id="addToWishlist" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content pt-3">
            <div class="modal-header">
                @if ($wishlistGroup->count())
                    <h4 class="modal-title">{{__('Add to which wishlist?')}}</h4>
                @else
                <h4 class="modal-title">{{__('Create your first wishlist')}}</h4>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="form-group">
                        @if ($wishlistGroup->count())
                            <select class="form-control" id="group" name="group">
                                @foreach ($wishlistGroup as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        @else
                            <label for="group" class="col-form-label">{{__('Name')}}:</label>
                            <input class="form-control" type="text" name="group" id="group">
                        @endif
                    </div>
                    <div class="row justify-content-end px-3">
                        <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="submit" class="btn btn-dark" id="add-to-wish" data-href="">
                            {{$wishlistGroup->count() ? __('ADD') : __('Create')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>