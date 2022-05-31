<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $data['events'] = Event::orderBy('id','desc')->get();
        return view('events.index', $data);
    }

    public function search_event(Request $request){
        if (isset($request->start_date) && isset($request->end_date)){
            $events = Event::whereBetween('event_date',[$request->start_date,$request->end_date])->orderBy('id','desc')->get();
//            dd($events->toArray());
            $html = '';
            $i = 1;
            foreach ($events as $event){
                $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$event->title.'</td>
                    <td>'.$event->type.'</td>
                    <td>'.$event->event_date.'</td>
                    <td>
                        <form action="'.route('events.destroy',$event->id).'" method="Post" id="deleteEventForm">
                            '.csrf_field().method_field('DELETE').'
                            <a class="btn btn-primary" href="'.route('events.edit',$event->id).'">Edit</a>
                            <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                        </form>
                    </td>
                </tr>';

                $i++;
            }

            return ['html' => $html];
        }
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'event_date' => 'required'
        ]);
        $Event = new Event();
        $Event->title = $request->title;
        $Event->type = $request->type;
        $Event->event_date = date('Y-m-d',strtotime($request->event_date));
        $Event->save();
        return redirect()->route('events.index')
            ->with('success','Event has been created successfully.');
    }

    public function show(Event $Event)
    {
        return view('events.show',compact('Event'));
    }

    public function edit(Event $Event)
    {
        return view('events.edit',compact('Event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'event_date' => 'required'
        ]);
        $Event = Event::find($id);
        $Event->title = $request->title;
        $Event->type = $request->type;
        $Event->event_date = date('Y-m-d',strtotime($request->event_date));
        $Event->save();
        return redirect()->route('events.index')
            ->with('success','Event Has Been updated successfully');
    }

    public function destroy(Event $Event)
    {
        $Event->delete();
        return redirect()->route('events.index')
            ->with('success','Event has been deleted successfully');
    }

    public function generate_pdf($start_date,$end_date){
//        dd($start_date, $end_date);
        $events = Event::whereBetween('event_date',[$start_date,$end_date])->orderBy('event_date','ASC')->get()->groupBy('event_date');
//        dd($events->toArray());
        try {
            $HTMLContent = '<style type="text/css">
                            <!--
                            table { vertical-align: top; }
                            tr    { vertical-align: top; }
                            td    { vertical-align: top; }
                            -->
                            </style>';

            foreach ($events as $key=>$event) {
                $HTMLContent .= '<page backcolor="#FEFEFE" style="font-size: 12pt">
                    <bookmark title="Lettre" level="0" ></bookmark>
                    <br>
                    <div style="width:100%; margin-top:0px; padding-top:0px; text-align: center; font-size: 15pt;"><b>Events of '.date('d M, Y', strtotime($key)).'</b></div><br>';

                $HTMLContent .= '<table cellspacing="0" style="width: 100%; margin-top:10px;  font-size: 10pt; margin-bottom:10px;" align="center">
                            <colgroup>
                                <col style="width: 20%; text-align: center">
                                <col style="width: 40%; text-align: center">
                                <col style="width: 20%; text-align: center">
                                <col style="width: 20%; text-align: center">
                            </colgroup>
                            <thead>
                                <tr style="background: #ffe6e6;">
                                    <th colspan="4" style="text-align: center; border-top : solid 1px gray; border-bottom: solid 1px grey;  padding:8px 0;"> Event Details </th>
                                </tr>
                                <tr>
                                    <th style="border-bottom: solid 1px gray; padding:8px 0;">No.</th>
                                    <th style="border-bottom: solid 1px gray; padding:8px 0;">Title</th>
                                    <th style="border-bottom: solid 1px gray; padding:8px 0;">Type</th>
                                    <th style="border-bottom: solid 1px gray; padding:8px 0;">Date</th>
                                </tr>
                            </thead>
                            <tbody>';

                $no = 1;
                foreach ($event as $ev) {
                    $HTMLContent .= '<tr>
                                    <th style="font-weight : 10px; padding:8px 0;">' . $no . '</th>
                                    <th style="font-weight : 10px; padding:8px 0;"><b>' . $ev->title . '</b></th>
                                    <th style="font-weight : 10px; padding:8px 0;">' . $ev->type . '</th>
                                    <th style="font-weight : 10px; padding:8px 0;">' . $ev->event_date . '</th>
                                </tr>';
                    $no++;
                }

                $HTMLContent .= '</tbody>
                        </table>
                        </page>';
            }

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($HTMLContent);
            $pdf = $html2pdf->Output('', 'S');
            $html2pdf->output('Event_' . time() . '.pdf');
            /*return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Length', strlen($pdf))
                ->header('Content-Disposition', 'inline; filename="Event_' . time() . '.pdf"');*/
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }

}
