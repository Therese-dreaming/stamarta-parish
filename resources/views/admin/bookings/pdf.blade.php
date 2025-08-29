<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking #{{ $booking->id }} Receipt</title>
    <style>
        @page { margin: 24mm 16mm; }
        body { font-family: Helvetica, Arial, sans-serif; color: #111; font-size: 12px; }
        .watermark { position: fixed; top: 40%; left: 10%; right: 10%; text-align: center; font-size: 140px; color: #111; opacity: 0.03; transform: rotate(-25deg); z-index: -1; font-weight: 700; }
        .hstack { display: table; width: 100%; }
        .left, .right { display: table-cell; vertical-align: middle; }
        .right { text-align: right; }
        .logo { height: 42px; width: 42px; margin-right: 8px; vertical-align: middle; }
        .brand { font-size: 18px; font-weight: 700; }
        .muted { color: #666; }
        .section { border: 1px solid #e5e5e5; border-radius: 6px; padding: 10px 12px; margin-top: 12px; }
        .row { display: table; width: 100%; table-layout: fixed; }
        .col { display: table-cell; vertical-align: top; padding: 6px; border: 1px solid #f0f0f0; background: #fafafa; }
        .label { font-size: 10px; color: #6b7280; }
        .value { font-size: 12px; font-weight: 600; }
        .footer { margin-top: 16px; font-size: 10px; color: #6b7280; display: table; width: 100%; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 600; background: #eef2ff; color: #3730a3; }
    </style>
</head>
<body>
    <div class="watermark">OFFICIAL</div>

    <div class="hstack">
        <div class="left">
            <img class="logo" src="{{ public_path('images/church-logo.png') }}" alt="Logo" />
            <span class="brand">Santa Marta Parish</span>
            <div class="muted">Booking Receipt</div>
        </div>
        <div class="right">
            <div class="value">Booking #{{ $booking->id }}</div>
            <div class="muted">Printed: {{ now()->format('M d, Y g:i A') }}</div>
        </div>
    </div>

    <div class="section">
        <div class="row">
            <div class="col">
                <div class="label">Service</div>
                <div class="value">{{ $booking->service->name }}</div>
            </div>
            <div class="col">
                <div class="label">Status</div>
                <div class="value"><span class="badge">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="label">Date & Time</div>
                <div class="value">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</div>
            </div>
            <div class="col">
                <div class="label">Fee</div>
                <div class="value">
                    @if($booking->payment)
                        {{ $booking->payment->formatted_total_fee }}
                    @else
                        Not yet recorded
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="row">
            <div class="col">
                <div class="label">Booked By</div>
                <div class="value">{{ $booking->user->name }}</div>
            </div>
            <div class="col">
                <div class="label">Email</div>
                <div class="value">{{ $booking->user->email }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="label">Phone</div>
                <div class="value">{{ $booking->contact_phone ?? '—' }}</div>
            </div>
            <div class="col">
                <div class="label">Address</div>
                <div class="value">{{ $booking->contact_address ?? '—' }}</div>
            </div>
        </div>
    </div>

    @if($booking->custom_data && count($booking->custom_data) > 0)
    @php $serviceType = $booking->service->service_type ?? 'general'; @endphp
    <div class="section">
        <div class="label" style="margin-bottom:6px">Service-Specific Information</div>

        @if(in_array($serviceType, ['solo_baptism', 'group_baptism']))
            <div class="row">
                @if(isset($booking->custom_data['child_first_name']) || isset($booking->custom_data['child_last_name']))
                <div class="col">
                    <div class="label">Child's Name</div>
                    <div class="value">
                        {{ $booking->custom_data['child_first_name'] ?? '' }} {{ $booking->custom_data['child_middle_initial'] ?? '' }} {{ $booking->custom_data['child_last_name'] ?? '' }}
                    </div>
                </div>
                @endif
                @if(isset($booking->custom_data['child_birth_date']))
                <div class="col">
                    <div class="label">Birth Date</div>
                    <div class="value">{{ \Carbon\Carbon::parse($booking->custom_data['child_birth_date'])->format('F d, Y') }}</div>
                </div>
                @endif
            </div>
            <div class="row">
                @if(isset($booking->custom_data['place_of_birth']))
                <div class="col">
                    <div class="label">Place of Birth</div>
                    <div class="value">{{ $booking->custom_data['place_of_birth'] }}</div>
                </div>
                @endif
                @if(isset($booking->custom_data['nationality']))
                <div class="col">
                    <div class="label">Nationality</div>
                    <div class="value">{{ $booking->custom_data['nationality'] }}</div>
                </div>
                @endif
            </div>
            <div class="row">
                @if(isset($booking->custom_data['father_first_name']) || isset($booking->custom_data['father_last_name']))
                <div class="col">
                    <div class="label">Father's Name</div>
                    <div class="value">{{ $booking->custom_data['father_first_name'] ?? '' }} {{ $booking->custom_data['father_middle_initial'] ?? '' }} {{ $booking->custom_data['father_last_name'] ?? '' }}</div>
                </div>
                @endif
                @if(isset($booking->custom_data['mother_first_name']) || isset($booking->custom_data['mother_last_name']))
                <div class="col">
                    <div class="label">Mother's Name</div>
                    <div class="value">{{ $booking->custom_data['mother_first_name'] ?? '' }} {{ $booking->custom_data['mother_middle_initial'] ?? '' }} {{ $booking->custom_data['mother_last_name'] ?? '' }}</div>
                </div>
                @endif
            </div>
            @if(isset($booking->custom_data['godparents']) && is_array($booking->custom_data['godparents']))
            <div class="row">
                <div class="col">
                    <div class="label">Godparents</div>
                    <div class="value">
                        @foreach($booking->custom_data['godparents'] as $index => $godparent)
                            <div>{{ $index + 1 }}. {{ $godparent }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @elseif($serviceType === 'wedding')
            <div class="row">
                @if(isset($booking->custom_data['groom_name']))
                <div class="col">
                    <div class="label">Groom</div>
                    <div class="value">{{ $booking->custom_data['groom_name'] }}</div>
                </div>
                @endif
                @if(isset($booking->custom_data['bride_name']))
                <div class="col">
                    <div class="label">Bride</div>
                    <div class="value">{{ $booking->custom_data['bride_name'] }}</div>
                </div>
                @endif
            </div>
            <div class="row">
                @if(isset($booking->custom_data['groom_birth_date']))
                <div class="col">
                    <div class="label">Groom Birth Date</div>
                    <div class="value">{{ \Carbon\Carbon::parse($booking->custom_data['groom_birth_date'])->format('F d, Y') }}</div>
                </div>
                @endif
                @if(isset($booking->custom_data['bride_birth_date']))
                <div class="col">
                    <div class="label">Bride Birth Date</div>
                    <div class="value">{{ \Carbon\Carbon::parse($booking->custom_data['bride_birth_date'])->format('F d, Y') }}</div>
                </div>
                @endif
            </div>
            @if(isset($booking->custom_data['witnesses']) && is_array($booking->custom_data['witnesses']))
            <div class="row">
                <div class="col">
                    <div class="label">Witnesses</div>
                    <div class="value">
                        @foreach($booking->custom_data['witnesses'] as $index => $witness)
                            <div>{{ $index + 1 }}. {{ $witness }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @elseif($serviceType === 'blessing')
            <div class="row">
                @if(isset($booking->custom_data['person_first_name']) || isset($booking->custom_data['person_last_name']))
                <div class="col">
                    <div class="label">Person's Name</div>
                    <div class="value">{{ $booking->custom_data['person_first_name'] ?? '' }} {{ $booking->custom_data['person_middle_initial'] ?? '' }} {{ $booking->custom_data['person_last_name'] ?? '' }}</div>
                </div>
                @endif
                @if(isset($booking->custom_data['blessing_type']))
                <div class="col">
                    <div class="label">Type of Blessing</div>
                    <div class="value">{{ ucfirst(str_replace('_', ' ', $booking->custom_data['blessing_type'])) }}</div>
                </div>
                @endif
            </div>
            @if(isset($booking->custom_data['blessing_details']))
            <div class="row">
                <div class="col">
                    <div class="label">Details</div>
                    <div class="value">{{ $booking->custom_data['blessing_details'] }}</div>
                </div>
            </div>
            @endif
        @else
            <div class="row">
                @foreach($booking->custom_data as $fieldKey => $fieldValue)
                    @if(is_string($fieldValue) || is_numeric($fieldValue))
                        <div class="col">
                            <div class="label">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}</div>
                            <div class="value">{{ $fieldValue }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
            @foreach($booking->custom_data as $fieldKey => $fieldValue)
                @if(is_array($fieldValue))
                <div class="row">
                    <div class="col">
                        <div class="label">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}</div>
                        <div class="value">
                            @foreach($fieldValue as $index => $item)
                                <div>{{ $index + 1 }}. {{ $item }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        @endif
    </div>
    @endif

    @if($booking->payment)
    <div class="section">
        <div class="row">
            <div class="col">
                <div class="label">Payment Status</div>
                <div class="value">{{ ucfirst($booking->payment->payment_status ?? 'N/A') }}</div>
            </div>
            <div class="col">
                <div class="label">Payment Method</div>
                <div class="value">{{ $booking->payment->payment_method_label ?? '—' }}</div>
            </div>
        </div>
        @if($booking->payment->payment_reference)
        <div class="row">
            <div class="col">
                <div class="label">Reference</div>
                <div class="value">{{ $booking->payment->payment_reference }}</div>
            </div>
            <div class="col">
                <div class="label">Amount</div>
                <div class="value">{{ $booking->payment->formatted_total_fee }}</div>
            </div>
        </div>
        @endif
    </div>
    @endif

    @if($booking->additional_notes)
    <div class="section">
        <div class="label">Notes</div>
        <div class="value" style="font-weight:500; line-height:1.5">{{ $booking->additional_notes }}</div>
    </div>
    @endif

    <div class="footer">
        <div class="left">Generated by Santa Marta Parish System</div>
        <div class="right">{{ config('app.name') }} • {{ config('app.url') }}</div>
    </div>
</body>
</html> 