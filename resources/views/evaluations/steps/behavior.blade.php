<div class="mt-8 space-y-6">

    @foreach($behaviorSections as $sectionKey => $section)

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">

                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $section['title'] }}
                </h3>

                <span
                    id="{{ $sectionKey }}-score"
                    class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-700">

                    {{ $section['max_score'] }} ពិន្ទុ

                </span>

            </div>

            {{-- Body --}}
            <div class="divide-y divide-gray-200">

                @foreach($section['items'] as $field => $item)

                    <div class="grid grid-cols-12 gap-6 items-center px-6 py-5">

                        <div class="col-span-8">

                            <div class="flex justify-between items-start gap-4">

                                <p class="text-gray-700">
                                    {{ $item['label'] }}
                                </p>

                                <span class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ $item['max_score'] }} ពិន្ទុ
                                </span>

                            </div>

                        </div>

                        <div class="col-span-4">

                            <div class="flex justify-end gap-6">

                                @for($i = 0; $i <= $item['max_score']; $i++)

                                    <label class="flex items-center gap-2 cursor-pointer">

                                        <input
                                            type="radio"
                                            name="{{ $field }}"
                                            value="{{ $i }}"
                                            class="behavior-radio">

                                        <span>{{ $i }}</span>

                                    </label>

                                @endfor

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endforeach

</div>