var app = app || {};

app.ManageNetworkView = Backbone.View.extend({
    initialize: function() {
        this.collection = new app.GalleryCollection();
        this.render();
        this.listenTo(this.collection, 'change', this.renderGallery);
    },
    events: {
        'change select.owned-by' : 'updateGalleryCollection'
    },
    render: function() {
        this.filterElement = $("<div></div>");
        this.galleryElement = $("<div class='node-gallery'></div>");
        this.$el.append(filterElement).append(galleryElement);
        this.$el.append(Mustache.render(app.TemplateManager.getTemplate('manage_network'), {}));
    },
    updateGalleryCollection: function(event) {
        var ownedBy = "Connecticut";
        this.collection.filterByOwner(ownedBy);
    },
    renderGallery: function() {
        var ctx = this;
        this.collection.each(function(image) {
            ctx.galleryElement.append(
                Mustache.render(
                    app.TemplateManager.getTemplate('node_gallery'),
                    image
                )
            );
        });
    },
    clickedBtn: function(event) {
        alert(event);
        alert('You clicked me!');
    }
});


app.GalleryCollection = Backbone.Collection.extend({
    initialize: function() {

    },
    model: Backbone.Model.extend({
        initialize : function() {
            this.listenTo(this, 'change:date')
            this.set('date', Date.parse(this.get('date')));
        }
    }),
    url: 'scripts/getGallery.php',
    filterByOwner: function(ownedBy) {
        this.fetch({data: {ownedBy: ownedBy}});
    }

});