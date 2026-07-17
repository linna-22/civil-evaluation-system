<div class="bg-white
           rounded-2xl
           shadow-sm
           overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full ">

            <thead class="bg-gray-50">

                <tr class="text-blue-500">

                    {{ $head }}

                </tr>

            </thead>

            <tbody id="{{ $bodyId ?? '' }}">

                {{ $body }}

            </tbody>

        </table>

    </div>

</div>