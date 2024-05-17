<?php
namespace Modules\User\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Models\CandidateContact;
use Modules\FrontendController;
use Modules\Gig\Models\Gig;

class ContactController extends FrontendController
{
    public function myContact(Request $request){

        if(is_candidate() && !is_admin()) {
            $query = CandidateContact::query()
                ->where(function ($q) {
                    $q->whereNull('contact_to')
                        ->orWhere('contact_to', 'candidate');
                })->where('origin_id', Auth::id());
        }else{
            $query = CandidateContact::query()
                ->where(function ($q) {
                    $q->whereNull('contact_to')
                        ->orWhere('contact_to', 'company');
                })->where('origin_id', Auth::id());
        }

        if($orderby = $request->get('order_by')){
            switch ($orderby){
                case 'oldest':
                    $query->orderBy('id', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $rows = $query->paginate(20);
        $data = [
            'rows' => $rows,
            'menu_active' => 'my_contact'
        ];
        return view('Contact::frontend.user.my-contact', $data);
    }
}
