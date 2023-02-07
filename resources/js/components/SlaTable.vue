<template>
    <div id="slatable">
        <div class="table-wrapper-scroll-y my-custom-scrollbar" style="width: 100%;">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col"><span style="color:#e20074;">Incident</span></th>
                        <th scope="col"><span style="color:#e20074;">SLA</span></th>
                        <th scope="col"><span style="color:#e20074;">Description</span></th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr v-for="valor in datatable">
                        <td><h5>{{ valor.incident }}</h5></td>
                        <td><h5>{{ valor.percentage }}%</h5></td>
                        <td><h5>{{ valor.description }}</h5></td>
                    </tr>
                    
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
export default {
    props:['datatable'],
}
</script>