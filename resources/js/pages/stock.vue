<template>
    <v-card>
      <v-card-title>
        <v-radio-group hide-details v-model="disponible" row>
            <v-radio label="Todos" value="todos"></v-radio>
            <v-radio label="Disponible" value="disponible"></v-radio>
            <v-radio label="No disponible" value="nodisponible"></v-radio>
        </v-radio-group>
      </v-card-title>
      <stocktable>
      </stocktable>
      <!--<v-data-table
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        :search="search"
        :headers="headers"
        :items="desserts"
        :options.sync="options"
        :server-items-length="totalDesserts"
        :loading="loading"
        class="elevation-1"
        show-expand
        show-select
        v-model="multiSalida"
      >
        <template v-slot:top>-->

          <!--<v-toolbar flat>
          <v-toolbar-title>Productos</v-toolbar-title>

          <v-spacer></v-spacer>
          
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn color="success" dark class="mb-2" v-on:click="multiInit()">Salida multiple</v-btn>
          <v-dialog persistent v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" dark class="mb-2" v-on:click="newItem()">New Item</v-btn>
            </template>
            <v-card  v-if="formCalc == false">
              <v-card-title>
                <span class="headline">{{ fullTitle }}</span>
              </v-card-title>
                <v-form
                  ref="form"
                  v-model="valid"
                >
                    <v-card-text>
                      <v-container grid-list-md>
                        <v-layout wrap>
                          <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.codbarras"
                              :items="comboboxes.fields.codbarras"
                              :loading="isLoading"
                              :search-input.sync="searchAuto"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="modelo"
                              item-value="id"
                              no-filter
                              label="Producto:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                              :rules="codbarrasRules"
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.modelo"></v-list-item-title>
                                    <v-list-item-subtitle v-if="data.item.ean" v-html="'EAN:' + data.item.ean"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-if="data.item.upc" v-html="'UPC:' + data.item.upc"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                            <v-flex xs18 sm9 md6>
                              <v-combobox
                                v-model="selectedItem.proveedor"
                                :items="comboboxes.fields.proveedores"
                                :search-input.sync="comboboxes.searching.proveedor"
                                hide-selected
                                hint="Seleccione la marca, si no existe escribala"
                                label="Proveedor"
                                no-filter
                                persistent-hint
                                :rules="proveedorRules"
                              >
                                <template v-slot:no-data>
                                  <v-list-item>
                                    <v-list-item-content>
                                      <v-list-item-title>
                                        No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                      </v-list-item-title>
                                    </v-list-item-content>
                                  </v-list-item>
                                </template>
                              </v-combobox>
                            </v-flex>
                            <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_entrada"
                            v-model="fecha_entrada"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_entrada"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_entrada"
                                label="Fecha de entrada"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                :rules="fecha_entradaRules"
                              ></v-text-field>
                            </template>
                            <v-date-picker locale="es-419" v-model="selectedItem.fecha_entrada" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_entrada = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_entrada.save(selectedItem.fecha_entrada)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_entrada" :rules="precio_entradaRules" type="numeric" label="Precio de entrada" required></v-text-field>  
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-menu
                            ref="fecha_salida"
                            v-model="fecha_salida"
                            :close-on-content-click="false"
                            :return-value.sync="selectedItem.fecha_salida"
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="selectedItem.fecha_salida"
                                label="Fecha de salida"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker  locale="es-419" v-model="selectedItem.fecha_salida" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="fecha_salida = false">Cancel</v-btn>
                              <v-btn text color="primary" @click="$refs.fecha_salida.save(selectedItem.fecha_salida)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                          <v-text-field v-model="selectedItem.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>  
                        </v-flex>
                        <v-flex xs18 sm9 md6>
                            <v-autocomplete
                              v-model="selectedItem.cliente"
                              :items="comboboxes.fields.clientes"
                              :loading="isLoading"
                              :search-input.sync="searchAutoClientes"
                              color="white"
                              hide-no-data
                              hide-selected
                              item-text="nombre"
                              item-value="id"
                              no-filter
                              label="Cliente:"
                              placeholder="Escribir para buscar"
                              prepend-icon="mdi-database-search"
                              return-object
                            >
                               <template v-slot:item="data">
                                  <v-list-item-content>
                                    <v-list-item-title v-html="data.item.nombre + ' '+ data.item.apellido"></v-list-item-title>
                                    <v-list-item-subtitle v-html="'DNI:' + data.item.documento"></v-list-item-subtitle>
                                    <v-list-item-subtitle v-html="'Email:' + data.item.email"></v-list-item-subtitle>
                                  </v-list-item-content>
                              </template>
                            </v-autocomplete>
                            </v-flex>
                        <v-flex xs36 sm18 md12 v-if="serializado == true">
                          <v-combobox
                            v-if="formTitle == 'Nuevo producto'"
                            v-model="selectedItem.seriales"
                            :items="comboboxes.fields.seriales"
                            :search-input.sync="comboboxes.searching.seriales"
                            hide-selected
                            small-chips
                            multiple
                            hint="Introduzca todos los seriales del mismo producto"
                            label="Seriales"
                            persistent-hint
                            :rules="serialesRules"
                          >
                            <template v-slot:no-data>
                              <v-list-item>
                                <v-list-item-content>
                                  <v-list-item-title>
                                    No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                  </v-list-item-title>
                                </v-list-item-content>
                              </v-list-item>
                            </template>
                          </v-combobox>
                          <v-combobox
                            v-else-if="formTitle == 'Editar producto'"
                            v-model="selectedItem.serial"
                            :items="comboboxes.fields.seriales"
                            :search-input.sync="comboboxes.searching.seriales"
                            hide-selected
                            hint="Introduzca todos los seriales del mismo producto"
                            label="Seriales"
                            persistent-hint
                            :rules="serialesRules"
                          >
                            <template v-slot:no-data>
                              <v-list-item>
                                <v-list-item-content>
                                  <v-list-item-title>
                                    No se encontraron resultados para "<strong>{{ comboboxes.searching.tipo }}</strong>". Presiona <kbd>enter</kbd> para crearlo
                                  </v-list-item-title>
                                </v-list-item-content>
                              </v-list-item>
                            </template>
                          </v-combobox>
                        </v-flex>
                        
                        
                          </v-layout>
                        </v-container>
                      </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn :disabled="!valid" color="success" text @click="save">Save</v-btn>
                  </v-card-actions>
                </v-form>
            </v-card>
            <v-card v-else>
                <v-card-title>
                  <span class="headline">¿Seguro que quiere eliminar el producto?</span>
                </v-card-title>
              <v-card-text>

                <v-simple-table
                  dense
                  fixed-header
                >
                  <tbody>
                    <tr>
                      <td>Marca:</td>
                      <td>{{ selectedItem.marca }}</td>
                    </tr>
                    <tr>
                      <td>Modelo:</td>
                      <td>{{ selectedItem.modelo }}</td>
                    </tr>
                    <tr>
                      <td>Serial:</td>
                      <td>{{ selectedItem.serial }}</td>
                    </tr>
                    <tr>
                      <td>Tipo de producto:</td>
                      <td>{{ selectedItem.tipo }}</td>
                    </tr>
                    <tr>
                      <td>Código de barras:</td>
                      <td>{{ selectedItem.codbarras }}</td>
                    </tr>
                    <tr>
                      <td>Proveedor:</td>
                      <td>{{ selectedItem.proveedor}}</td>
                    </tr>
                    <tr>
                      <td>Fecha de entrada:</td>
                      <td>{{ selectedItem.fecha_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Precio de entrada:</td>
                      <td>{{ selectedItem.precio_entrada }}</td>
                    </tr>
                    <tr>
                      <td>Fecha de salida:</td>
                      <td>{{ selectedItem.fecha_salida }}</td>
                    </tr>
                    <tr>
                      <td>Precio de salida:</td>
                      <td>{{ selectedItem.precio_salida }}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
              </v-card-text>
              <template>
                <v-spacer></v-spacer>
              </template>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>-->
      <!--</template>-->
      <!--<template v-slot:expanded-item="{ item }">
        <td :colspan="headers.length">
          <v-simple-table
                  fixed-header
                  allign="center"
                >
                  <tbody>
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Documento</th>
                      <th>Email</th>
                      <th>Domicilio</th>
                    </tr>
                    <tr>
                      <td>{{item.nombre}}</td>
                      <td>{{item.apellido}}</td>
                      <td>{{item.documento}}</td>
                      <td>{{item.email}}</td>
                      <td>{{item.domicilio}}</td>
                    </tr>
                  </tbody>
                </v-simple-table>
        </td>
      </template>
    <template v-slot:item.action="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="outItem(item)"
      >
        done
      </v-icon>
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        edit
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        delete
      </v-icon>
    </template>
      </v-data-table>-->
      <v-dialog persistent v-model="multiDialog">
        <v-card>
          <v-card-text>
            <v-data-table :items="multiSalida" :headers="headers">
              <template v-slot:item.fecha_salida="{ item }">
                <v-menu
                  :ref="'fecha_salida' +item.id"
                  v-model="item.fecha_open"
                  :close-on-content-click="false"
                  :return-value.sync="item.fecha_open"
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="item.fecha_salida"
                    label="Fecha de salida"
                    prepend-icon="event"
                    readonly
                    v-on="on"
                  ></v-text-field>
                </template>
                  <v-date-picker  locale="es-419" v-model="item.fecha_salida" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="item.fecha_open = false">Cancel</v-btn>
                    <v-btn text color="primary" @click="$refs['fecha_salida' + item.id].save(item.fecha_salida)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </template>
              <template v-slot:item.precio_salida="{ item }">
                <v-text-field v-model="item.precio_salida" type="numeric" label="Precio de salida" required></v-text-field>
              </template>
            </v-data-table>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="multiClose">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="multiSave">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card>
</template>
<script>
  import stocktable from '../components/tables/stocktable'
  export default {
    components:
    {
      stocktable
    },
    data () {
      return {
        salidaTotal: 0,
        item_fecha_salida: false,
        multiprecio: [],
        multiDialog: false,
        multiSalida: [],
        singleExpand: false,
        expanded: [],
        descriptionLimit: 60,
        entries: [],
        isLoading: false,
        isLoadingClientes: false,
        model: null,
        searchAuto: null,
        searchAutoClientes: null,
        serialesRules: [
          v => !!v || 'Debe ingresar al menos un serial'
        ],
        codbarrasRules: [
          v => !!v || 'Producto requerido',
        ],
        precio_entradaRules: [
          v => !!v || 'Precio de entrada requerido'
        ],
        proveedorRules: [
          v => !!v || 'Proveedor requerido',
          //v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        fecha_entradaRules: [
          v => !!v || 'Fecha de entrada requerida'
        ],
        /*fecha_salidaRules: [
          v => !!v || 'Fecha de salida requerida'
        ],*/
        //disponible: "todos",
        valid: true,
        formTitle: "",
        fecha_entrada: false,
        fecha_salida: false,
        comboboxes:
        {
          fields:{
            codbarras: [],
            tipos: [],
            marcas: [],
            proveedores: [],
            seriales: [],
            clientes: []
          },
          searching:{
            codbarras: null,
            tipo: null,
            marca: null,
            proveedor: null,
            seriales: null
          },
          loading:
          {
            proveedores: false,
            clientes: false
          }
        },
        dialog: false,
        search:'',
        totalDesserts: 0,
        desserts: [],
        loading: true,
        options: {},
        headers: [
          //{ text: 'Código UPC', value: 'upc' },
          //{ text: 'Código EAN', value: 'ean'},
          //{ text: 'Tipo de producto', value: 'tipo' },
          { text: 'Modelo', value: 'modelo' },
          { text: 'Marca', value: 'marca' },
          { text: 'Serial', value: 'serial'},
          { text: 'Proveedor', value: 'proveedor'},
          { text: 'Fecha de entrada', value: 'fecha_entrada'},
          { text: 'Precio de entrada', value: 'precio_entrada'},
          { text: 'Fecha de salida', value: 'fecha_salida'},
          { text: 'Precio de salida', value: 'precio_salida'},
          { text: 'Actions', value: 'action', sortable: false }
        ],
        headers: [
          //{ text: 'Código UPC', value: 'upc' },
          //{ text: 'Código EAN', value: 'ean'},
          //{ text: 'Tipo de producto', value: 'tipo' },
          { text: 'Modelo', value: 'modelo' },
          { text: 'Marca', value: 'marca' },
          { text: 'Serial', value: 'serial'},
          { text: 'Proveedor', value: 'proveedor'},
          { text: 'Fecha de entrada', value: 'fecha_entrada'},
          { text: 'Precio de entrada', value: 'precio_entrada'},
          { text: 'Fecha de salida', value: 'fecha_salida'},
          { text: 'Precio de salida', value: 'precio_salida'},
          { text: 'Actions', value: 'action', sortable: false }
        ],
        defaultItem: {
          id: 0,
          codbarras: null,
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: null,
          fecha_entrada: null,
          fecha_salida: null,
          precio_entrada: null,
          precio_salida: null,
          seriales: null,
          cliente: null
        },
        selectedItem: {
          id: 0,
          codbarras: null,
          tipo: "",
          marca: "",
          modelo: "",
          proveedor: null,
          fecha_entrada: null,
          fecha_salida: null,
          precio_entrada: null,
          precio_salida: null,
          seriales: null,
          cliente: null
        }
      }
    },
    computed:
    {
      disponible: {
        get()
        {
          return this.$store.getters.getDisponible
        },
        set(value)
        {
          this.$store.commit('setDisponible', value)
        }
      },
      fullTitle: function()
      {
        if(this.selectedItem.codbarras)
        {
          return this.formTitle + ': ' + this.selectedItem.codbarras.modelo
        }
        else
        {
          return this.formTitle
        }
      },
      formCalc: function()
      {
        if(this.formTitle == 'Eliminar producto' || this.formTitle == 'Salida producto')
        {
          return true
        }
        else
        {
          return false
        }

      },
      serializado: function()
      {
        if(this.selectedItem.codbarras)
        {
          if(this.selectedItem.codbarras.serializado == true)
          {
            return true
          }
        }
        return false
      }
    },
    watch: {
      multiSalida:
      {
        handler ()
        {
          if(this.multiSalida.length > this.salidaTotal)
          {
            this.multiSalida[this.multiSalida.length -1].fecha_open = false;
            this.salidaTotal = this.salidaTotal +1;
          }
          else
          {
            this.salidaTotal = this.salidaTotal -1;
          }
        },
        deep: true
      },
      disponible:
      {
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      search:{
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      options: {
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
      dialog (val) {
        val || this.close()
      },
      searchAuto (val) {
        this.isLoading = true
        axios.post('/ajax/codbarras',
          {
            search:val
          }).then(response => {
            this.comboboxes.fields.codbarras = response.data;
            this.isLoading = false;
        });
      },
      searchAutoClientes(val)
      {
        this.comboboxes.loading.clientes = true
        axios.post('/ajax/clientes',
        {
          search: val
        }).then(response =>{
          this.comboboxes.fields.clientes = response.data;
          this.isLoadingClientes = false;
        });
      },
      'comboboxes.searching.proveedor'(val)
      {
        this.comboboxes.loading.proveedores = true
        axios.post('/ajax/proveedores',
              {
                search: val  
              }).then(response => {
                this.comboboxes.fields.proveedores = response.data;
                this.comboboxes.loading.proveedores = false
              });
      }
    },
    methods: {
      multiInit()
      {
        this.multiDialog = true
      },
      fields () {
        if (!this.model) return []

        return Object.keys(this.model).map(key => {
          return {
            key,
            value: this.model[key] || 'n/a',
          }
        })
      },
      items () {
        return this.entries.map(entry => {
          const Description = entry.Description.length > this.descriptionLimit
            ? entry.Description.slice(0, this.descriptionLimit) + '...'
            : entry.Description

          return Object.assign({}, entry, { Description })
        })
      },
      newItem ()
      {
        this.formTitle = "Nuevo producto"
        this.selectedItem = Object.assign({}, this.defaultItem)
        this.dialog = true  
      },
      editItem (item) {
        this.formTitle = "Editar producto"
        if(item.upc != null)
        {
          this.searchAuto = item.upc;
        }
        else
        {
          this.searchAuto = item.ean;
        }
        this.searchAutoClientes = item.nombre;
        this.selectedItem = Object.assign({}, {
          id: item.id,
          codbarras: {
            id: item.productos_id,
            upc: item.upc,
            ean: item.ean,
            modelo: item.modelo,
            serializado: item.serializado
          },
          tipo: item.tipo,
          marca: item.marca,
          modelo: item.modelo,
          proveedor: {
            value: item.proveedores_id,
            text: item.proveedor
          },
          cliente: {
            nombre: item.nombre,
            apellido: item.apellido,
            id: item.clienteid,
            documento: item.documento,
            domicilio: item.domicilio,
            correo: item.correo
          },
          fecha_entrada: item.fecha_entrada,
          fecha_salida: item.fecha_salida,
          precio_entrada: item.precio_entrada,
          precio_salida: item.precio_salida,
          serial: item.serial
        })
        this.dialog = true
      },

      deleteItem (item) {
        this.formTitle = "Eliminar producto"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },
      outItem (item)
      {
        this.formTitle = "Salida producto"
        this.selectedItem = Object.assign({}, item)
        this.dialog = true
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.selectedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.getDataFromApi();
        }, 300)
      },

      save () {
        if(this.formTitle == "Nuevo producto")
        {
          axios.post('/admin/stock/nuevo',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Editar producto")
        {
          axios.post('/admin/stock/editar',
                this.selectedItem).then(response => {
                  
                });
        }
        else if(this.formTitle == "Eliminar producto")
        {
          axios.post('/admin/stock/eliminar',
                {id:this.selectedItem.id}).then(response => {
                    
                });   
        }
        this.close()
      },
      //CARGAR COMBOBOX
      //cargarSelects: function()
      //{
        //this.getTiposProductos();
        //this.getMarcas();
        //this.getCodbarras();
        //this.getProveedores();
        //this.getSeriales();
      //},
      getTiposProductos: function()
      {
          axios.post('/ajax/tiposprods')
            .then(response => {
              this.comboboxes.fields.tipos = response.data;
          });
      },
      getMarcas: function()
      {
        axios.get('/ajax/marcas')
          .then(response => {
            this.comboboxes.fields.marcas = response.data;    
        });
      },
      getCodbarras()
      {
        axios.get('/ajax/codbarras')
            .then(response => {
              this.comboboxes.fields.codbarras = response.data;
        });
      },
      getProveedores()
      {
          axios.get('/ajax/proveedores')
              .then(response => {              
                  this.comboboxes.fields.proveedores = response.data;
          });
      },
      getSeriales()
      {
        axios.get('/ajax/seriales')
            .then(response => {
              var a = [];
              console.log(response.data)
              response.data.forEach(function(item){
                            a.push({
                              value: item.value,
                              disabled: true,
                              text: item.text
                            });
              }); 
              this.comboboxes.fields.seriales = a;
            
            });
      },
      multiClose()
      {
        this.multiDialog = false;

      },
      multiSave()
      {
        axios.post('/stock/multisalida',
          this.multiSalida
        ).then(response=> {
          console.log(response)
        });
      }
    },
  }
</script>